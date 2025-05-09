<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', true);
        
        // Apply category filter if specified
        if ($request->has('category')) {
            $categoryIds = $request->category;
            $query->whereHas('categories', function($q) use ($categoryIds) {
                // If we have multiple categories, use whereIn
                if (is_array($categoryIds)) {
                    $q->whereIn('categories.category_id', $categoryIds);
                } else {
                    // Backward compatibility for single category
                    $q->where('categories.category_id', $categoryIds);
                }
            });
        }
        
        // Apply price range filter if specified
        if ($request->filled('price_from')) {
            $query->where('price', '>=', (float)$request->price_from);
        }
        
        if ($request->filled('price_to')) {
            $query->where('price', '<=', (float)$request->price_to);
        }
        
        // Apply in_stock filter if specified
        if ($request->has('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }
        
        // Apply search if specified
        if ($request->has('search') && !empty($request->search)) {
            $search = trim($request->search);
            $searchNormalized = $this->normalizeString($search);
            
            $query->where(function($q) use ($search, $searchNormalized) {
                // Search using normalized database columns for accent-insensitive search
                // This will find "stôl" when searching for "stol"
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('TRANSLATE(LOWER(name), \'áäčďéíĺľňóôŕšťúýž\', \'aacdeillnoorstuyz\') LIKE ?', ['%' . $searchNormalized . '%'])
                  ->orWhereRaw('TRANSLATE(LOWER(description), \'áäčďéíĺľňóôŕšťúýž\', \'aacdeillnoorstuyz\') LIKE ?', ['%' . $searchNormalized . '%']);
                
                // For multi-word searches, also search individual words
                $words = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
                if (count($words) > 1) {
                    foreach ($words as $word) {
                        if (strlen($word) >= 2) { // Only search for words with 2+ characters
                            $wordNormalized = $this->normalizeString($word);
                            $q->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%'])
                              ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($word) . '%'])
                              ->orWhereRaw('TRANSLATE(LOWER(name), \'áäčďéíĺľňóôŕšťúýž\', \'aacdeillnoorstuyz\') LIKE ?', ['%' . $wordNormalized . '%'])
                              ->orWhereRaw('TRANSLATE(LOWER(description), \'áäčďéíĺľňóôŕšťúýž\', \'aacdeillnoorstuyz\') LIKE ?', ['%' . $wordNormalized . '%']);
                        }
                    }
                }
            });
        }
        
        // Apply sorting
        $sortBy = $request->input('sort', 'newest');
        
        switch ($sortBy) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'bestselling':
                // You would need a sales_count column or similar to implement this
                $query->orderBy('created_at', 'desc'); // Fallback to newest
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Get all categories for filter sidebar
        $categories = Category::all();
        
        // Paginate the results
        $products = $query->paginate(9);
        
        // Pass both products and categories to the view
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Remove accents and convert to lowercase
     */
    private function normalizeString($string) 
    {
        // Special handling for common Slovak characters
        $from = ['á', 'ä', 'č', 'ď', 'é', 'í', 'ĺ', 'ľ', 'ň', 'ó', 'ô', 'ŕ', 'š', 'ť', 'ú', 'ý', 'ž', 
                'Á', 'Ä', 'Č', 'Ď', 'É', 'Í', 'Ĺ', 'Ľ', 'Ň', 'Ó', 'Ô', 'Ŕ', 'Š', 'Ť', 'Ú', 'Ý', 'Ž'];
        $to = ['a', 'a', 'c', 'd', 'e', 'i', 'l', 'l', 'n', 'o', 'o', 'r', 's', 't', 'u', 'y', 'z',
              'a', 'a', 'c', 'd', 'e', 'i', 'l', 'l', 'n', 'o', 'o', 'r', 's', 't', 'u', 'y', 'z'];
              
        // Replace Slovak-specific characters
        $normalized = str_replace($from, $to, mb_strtolower($string, 'UTF-8'));
        
        // Use Laravel's ASCII converter as a fallback for other accents
        if ($normalized === mb_strtolower($string, 'UTF-8')) {
            $normalized = Str::ascii(mb_strtolower($string, 'UTF-8'));
        }
        
        return $normalized;
    }
    
    /**
     * Add a fallback search for databases that don't support unaccent
     */
    private function addFallbackSearch($query, $normalizedTerm, $normalizedWords)
    {
        // For databases without unaccent function, try with PHP's accent removal function
        $query->orWhere(function($q) use ($normalizedTerm) {
            $q->whereRaw('LOWER(name) LIKE ?', ['%' . $normalizedTerm . '%'])
              ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $normalizedTerm . '%']);
        });
        
        // For multi-word terms, also search for each normalized word
        if (count($normalizedWords) > 1) {
            foreach ($normalizedWords as $word) {
                if (strlen($word) >= 2) {
                    $query->orWhere(function($q) use ($word) {
                        $q->whereRaw('LOWER(name) LIKE ?', ['%' . $word . '%'])
                          ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $word . '%']);
                    });
                }
            }
        }
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with('images', 'categories')->findOrFail($id);
        
        return view('products.show', compact('product'));
    }
}

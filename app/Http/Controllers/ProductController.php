<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
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
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with('images', 'categories')->findOrFail($id);
        
        return view('products.show', compact('product'));
    }
}

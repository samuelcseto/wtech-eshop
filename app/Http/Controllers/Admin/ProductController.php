<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // We can't use middleware() method directly in Laravel 12
    }
    
    /**
     * Check if the current user is an admin.
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
        }
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        
        $products = Product::with('images')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'is_active' => true,
            'slug' => Str::slug($request->name),
        ]);

        // Handle categories
        if ($request->has('categories')) {
            // Filter out any empty values
            $categories = array_filter($request->categories, function($value) {
                return !empty($value);
            });
            
            if (!empty($categories)) {
                $product->categories()->sync($categories);
            }
        }

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    // Check if the file is valid
                    if ($image->isValid()) {
                        $path = $image->store('products', 'public');
                        
                        $productImage = new ProductImage([
                            'product_id' => $product->product_id, // Explicitly set product_id
                            'image_url' => 'storage/' . $path,
                            'is_primary' => false, // Set first image as primary later
                            'alt_text' => $product->name, // Set a default alt text
                            'sort_order' => 0 // Default sort order
                        ]);
                        
                        $productImage->save(); // Save directly instead of using relationship
                    } else {
                        // Log the error but continue with other images
                        \Log::error('Invalid image file uploaded: ' . $image->getClientOriginalName());
                    }
                } catch (\Exception $e) {
                    // Log the exception but continue with other images
                    \Log::error('Error uploading image: ' . $e->getMessage());
                }
            }
            
            // Set first image as primary if exists
            $firstImage = ProductImage::where('product_id', $product->product_id)->first();
            if ($firstImage) {
                $firstImage->is_primary = true;
                $firstImage->save();
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        
        $product = Product::with(['images', 'categories'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        
        $product = Product::with(['images', 'categories'])->findOrFail($id);
        $categories = Category::all();
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'slug' => Str::slug($request->name),
        ]);

        // Handle categories
        if ($request->has('categories')) {
            // Filter out any empty values
            $categories = array_filter($request->categories, function($value) {
                return !empty($value);
            });
            
            if (!empty($categories)) {
                $product->categories()->sync($categories);
            } else {
                $product->categories()->detach();
            }
        } else {
            $product->categories()->detach();
        }

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    // Check if the file is valid
                    if ($image->isValid()) {
                        $path = $image->store('products', 'public');
                        
                        $productImage = new ProductImage([
                            'product_id' => $product->product_id, // Explicitly set product_id
                            'image_url' => 'storage/' . $path,
                            'is_primary' => false,
                            'alt_text' => $product->name, // Set a default alt text
                            'sort_order' => 0 // Default sort order
                        ]);
                        
                        $productImage->save(); // Save directly instead of using relationship
                    } else {
                        // Log the error but continue with other images
                        \Log::error('Invalid image file uploaded: ' . $image->getClientOriginalName());
                    }
                } catch (\Exception $e) {
                    // Log the exception but continue with other images
                    \Log::error('Error uploading image: ' . $e->getMessage());
                }
            }
        }

        // Handle primary image
        if ($request->has('primary_image')) {
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('image_id', $request->primary_image)->update(['is_primary' => true]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        
        $product = Product::findOrFail($id);
        
        // Delete product images from storage
        foreach($product->images as $image) {
            // Remove the 'storage/' prefix from image_url
            $path = str_replace('storage/', '', $image->image_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        // Delete the product
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
    
    /**
     * Delete a specific product image.
     */
    public function deleteImage($productId, $imageId)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        
        $image = ProductImage::findOrFail($imageId);
        
        // Check if image belongs to the product
        if ($image->product_id != $productId) {
            return redirect()->back()->with('error', 'Image does not belong to this product.');
        }
        
        // Delete image from storage
        $path = str_replace('storage/', '', $image->image_url);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // Delete image from database
        $image->delete();
        
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}

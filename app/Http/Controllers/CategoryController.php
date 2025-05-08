<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display products from a specific category.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        
        // Redirect to products index with the category filter applied
        return redirect()->route('products.index', ['category' => $category->category_id]);
    }
}
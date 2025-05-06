<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PageController extends Controller
{
    public function index()
    {
        $products = Product::with('primaryImage')->limit(6)->get();
        $categories = Category::get();
        //dd($products[0]->primaryImage->image_url);
        return view('landing', compact('products', 'categories'));
    }
}

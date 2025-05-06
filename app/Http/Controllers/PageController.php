<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class PageController extends Controller
{
    public function index()
    {
        $products = Product::limit(6)->get();
        return view('landing', compact('products'));
    }
}

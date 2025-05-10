<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->with(['items.product.images', 'shippingProvider'])
            ->get();
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::where('order_id', $id)
            ->where('user_id', Auth::id())
            ->with(['items.product.images', 'shippingProvider'])
            ->firstOrFail();
        
        return view('orders.show', compact('order'));
    }
}
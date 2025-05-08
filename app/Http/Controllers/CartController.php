<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Get or create a cart
     */
    protected function getCart()
    {
        if (Auth::check()) {
            // User is logged in - get or create their cart
            $cart = Auth::user()->carts()
                ->latest()
                ->first();
            
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                ]);
            }
        } else {
            // User is a guest - use session ID to track cart
            $sessionId = session()->getId();
            
            // Try to find an existing cart with this session ID
            $cart = Cart::where('session_id', $sessionId)
                ->latest()
                ->first();
            
            if (!$cart) {
                $cart = Cart::create([
                    'session_id' => $sessionId,
                ]);
            }
        }
        
        return $cart;
    }
    
    /**
     * Show the shopping cart
     */
    public function show(Request $request)
    {
        $cart = $this->getCart();
        
        // Eager load cart items with their products
        $cart->load(['items.product.primaryImage']);
        
        // Calculate totals
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        // Add shipping cost (fixed amount for now)
        $shipping = 3.00;
        $total = $subtotal + $shipping;
        
        return view('cart.show', compact('cart', 'subtotal', 'shipping', 'total'));
    }
    
    /**
     * Add a product to the shopping cart
     */
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        
        // Validate quantity
        if ($quantity <= 0) {
            return back()->with('error', 'Quantity must be at least 1.');
        }
        
        // Check stock availability
        if ($product->stock_quantity < $quantity) {
            return back()->with('error', 'Not enough items in stock.');
        }
        
        // Get attributes if any
        $attributes = $request->except(['_token', 'quantity']);
        $attributesJson = !empty($attributes) ? json_encode($attributes) : null;
        
        // Get or create cart
        $cart = $this->getCart();
        
        // Check if product already exists in cart
        $cartItem = $cart->items()
            ->where('product_id', $product->product_id)
            ->where('attributes', $attributesJson)
            ->first();
        
        DB::beginTransaction();
        try {
            if ($cartItem) {
                // Update existing cart item
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->cart_id,
                    'product_id' => $product->product_id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'attributes' => $attributesJson,
                ]);
            }
            
            DB::commit();
            return back()->with('success', 'Product added to cart successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add product to cart.');
        }
    }
    
    /**
     * Update cart item quantity
     */
    public function update(Request $request, $itemId)
    {
        $quantity = (int) $request->input('quantity');
        
        // Validate quantity
        if ($quantity <= 0) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Quantity must be at least 1.']);
            }
            return back()->with('error', 'Quantity must be at least 1.');
        }
        
        $cart = $this->getCart();
        $cartItem = $cart->items()->where('cart_item_id', $itemId)->first();
        
        if (!$cartItem) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Cart item not found.']);
            }
            return back()->with('error', 'Cart item not found.');
        }
        
        // Check stock availability
        if ($cartItem->product->stock_quantity < $quantity) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Not enough items in stock.']);
            }
            return back()->with('error', 'Not enough items in stock.');
        }
        
        DB::beginTransaction();
        try {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            
            DB::commit();
            
            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully!',
                    'cart_count' => $cart->getTotalItemsAttribute(),
                    'cart_total' => $cart->getTotalAttribute(),
                    'item_subtotal' => $cartItem->price * $cartItem->quantity
                ]);
            }
            
            return back()->with('success', 'Cart updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Failed to update cart.']);
            }
            
            return back()->with('error', 'Failed to update cart.');
        }
    }
    
    /**
     * Remove a product from the shopping cart
     */
    public function remove(Request $request, $itemId)
    {
        $cart = $this->getCart();
        $cartItem = $cart->items()->where('cart_item_id', $itemId)->first();
        
        if (!$cartItem) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Item not found in your cart.']);
            }
            return back()->with('error', 'Item not found in your cart.');
        }
        
        try {
            $cartItem->delete();
            
            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart!',
                    'cart_count' => $cart->getTotalItemsAttribute(),
                    'cart_total' => $cart->getTotalAttribute()
                ]);
            }
            
            return back()->with('success', 'Item removed from cart!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Failed to remove item from cart.']);
            }
            return back()->with('error', 'Failed to remove item from cart.');
        }
    }
    
    /**
     * Clear the entire shopping cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        
        return back()->with('success', 'Cart cleared successfully!');
    }
    
    /**
     * Show the checkout page (second cart step)
     */
    public function checkout()
    {
        $cart = $this->getCart();
        
        // Check if cart has items
        if ($cart->items->count() === 0) {
            return redirect()->route('cart.show')
                ->with('error', 'Váš košík je prázdny. Najskôr pridajte produkty.');
        }
        
        // Eager load cart items with their products
        $cart->load(['items.product.primaryImage']);
        
        // Calculate totals
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        // Default shipping cost (will be updated by JS)
        $shipping = 2.49; // Default to the cheapest option
        $total = $subtotal + $shipping;
        
        // Add cart preview variables for the header
        $cart_items = $cart->items;
        $cart_count = $cart->items->sum('quantity');
        $cart_total = $subtotal;
        
        // Get active countries with their shipping providers
        $countries = \App\Models\Country::where('is_active', true)
            ->with(['shippingProviders' => function($query) {
                $query->where('is_active', true)
                      ->orderBy('price', 'asc');
            }])
            ->get();
            
        // Filter out countries without active shipping providers
        $countries = $countries->filter(function($country) {
            return $country->shippingProviders->count() > 0;
        });
        
        return view('cart.checkout', compact(
            'cart', 
            'subtotal', 
            'shipping', 
            'total', 
            'cart_items', 
            'cart_count', 
            'cart_total', 
            'countries'
        ));
    }
    
    /**
     * Merge guest cart with user cart on login
     */
    public function mergeCart($sessionId, $userId)
    {
        // Find the guest cart by session ID
        $guestCart = Cart::where('session_id', $sessionId)
            ->where('user_id', null)
            ->latest()
            ->first();
        
        if (!$guestCart) {
            return;
        }
        
        // Find or create a cart for the logged-in user
        $userCart = Cart::where('user_id', $userId)->latest()->first();
        
        if (!$userCart) {
            // Simply assign the guest cart to the user
            $guestCart->user_id = $userId;
            $guestCart->save();
            return;
        }
        
        // Merge guest cart items into the user's cart
        foreach ($guestCart->items as $guestItem) {
            $userItem = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('attributes', $guestItem->attributes)
                ->first();
            
            if ($userItem) {
                // If the item exists in user's cart, update quantity
                $userItem->quantity += $guestItem->quantity;
                $userItem->save();
                
                // Delete guest item
                $guestItem->delete();
            } else {
                // Move the item to the user's cart
                $guestItem->cart_id = $userCart->cart_id;
                $guestItem->save();
            }
        }
        
        // Delete the now-empty guest cart
        $guestCart->delete();
    }
}
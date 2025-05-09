<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
     * Show the checkout page (first cart step)
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
     * Store checkout form data in cart and validate
     */
    public function storeCheckout(Request $request)
    {
        // Simple validation for required fields
        $validator = validator($request->all(), [
            'email' => 'required|email',
            'phone' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal' => 'required',
            'country' => 'required',
            'shippingMethod' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Prosím, vyplňte všetky povinné polia.'
            ]);
        }

        // Get cart and store form data directly in the cart
        $cart = $this->getCart();
        
        try {
            // Update cart with checkout data
            $cart->update([
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'newsletter' => $request->has('newsletter'),
                'first_name' => $request->input('firstName'),
                'last_name' => $request->input('lastName'),
                'address_line1' => $request->input('address'),
                'address_line2' => $request->input('address2'),
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal'),
                'country' => $request->input('country'),
                'shipping_provider_id' => $request->input('shippingMethod'),
            ]);
            
            // Keep the session for backward compatibility
            Session::put('checkout.contact', [
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'newsletter' => $request->has('newsletter'),
            ]);

            Session::put('checkout.shipping', [
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'address' => $request->input('address'),
                'address2' => $request->input('address2'),
                'city' => $request->input('city'),
                'postal' => $request->input('postal'),
                'country' => $request->input('country'),
            ]);

            Session::put('checkout.shippingMethod', $request->input('shippingMethod'));

            return response()->json([
                'success' => true,
                'message' => 'Údaje úspešne uložené.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vyskytla sa chyba pri ukladaní údajov.'
            ]);
        }
    }

    /**
     * Show the payment page (second cart step)
     */
    public function payment()
    {
        $cart = $this->getCart();
        
        // Check if cart has items
        if ($cart->items->count() === 0) {
            return redirect()->route('cart.show')
                ->with('error', 'Váš košík je prázdny. Najskôr pridajte produkty.');
        }

        // Check if checkout data has been filled
        if (!$cart->email || !$cart->phone || !$cart->first_name || !$cart->shipping_provider_id) {
            return redirect()->route('cart.checkout')
                ->with('error', 'Najprv musíte vyplniť kontaktné a doručovacie údaje.');
        }
        
        // Eager load cart items with their products
        $cart->load(['items.product.primaryImage', 'shippingProvider']);
        
        // Calculate totals
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        // Get the shipping cost from the shipping provider
        $shipping = $cart->shippingProvider ? $cart->shippingProvider->price : 2.49; // Default if not found
        
        $total = $subtotal + $shipping;
        
        // Get active countries for billing form
        $countries = \App\Models\Country::where('is_active', true)->get();
        
        return view('cart.payment', compact(
            'cart', 
            'subtotal', 
            'shipping', 
            'total',
            'countries'
        ));
    }

    /**
     * Process payment and complete order
     */
    public function processPayment(Request $request)
    {
        // Validate based on selected payment method
        $validator = validator($request->all(), [
            'payment_method' => 'required|in:CARD,COD,WIRE',
        ]);

        // Add validation rules for card payment
        if ($request->input('payment_method') === 'CARD') {
            $validator = validator($request->all(), [
                'payment_method' => 'required',
                'card_number' => 'required|digits:16',
                'expiry_month' => 'required|numeric|between:1,12',
                'expiry_year' => 'required|numeric|between:23,99',
                'security_code' => 'required|digits:3'
            ]);
        }

        // Check if validation fails
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $cart = $this->getCart();
        
        // Check if cart has items
        if ($cart->items->count() === 0) {
            return redirect()->route('cart.show')
                ->with('error', 'Váš košík je prázdny. Najskôr pridajte produkty.');
        }

        // Check if checkout data is present in the cart
        if (!$cart->email || !$cart->phone || !$cart->first_name || !$cart->shipping_provider_id) {
            return redirect()->route('cart.checkout')
                ->with('error', 'Chýbajú údaje objednávky. Prosím, vyplňte všetky požadované údaje.');
        }

        // Update cart with payment method
        $cart->payment_method = $request->input('payment_method');
        $cart->save();

        // Calculate totals
        $cart->load(['items.product', 'shippingProvider']);
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Get shipping cost directly from the shipping provider stored in cart
        $shippingCost = 0;
        if ($cart->shippingProvider) {
            $shippingCost = $cart->shippingProvider->price;
        }
        
        // Calculate total
        $totalAmount = $subtotal + $shippingCost;

        // Add COD fee if applicable
        if ($request->input('payment_method') === 'COD') {
            $codFee = 1.50;
            $totalAmount += $codFee;
        }

        DB::beginTransaction();
        try {
            // Create the order
            $order = Order::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'email' => $cart->email,
                'phone' => $cart->phone,
                'first_name' => $cart->first_name,
                'last_name' => $cart->last_name,
                'address_line1' => $cart->address_line1,
                'address_line2' => $cart->address_line2,
                'city' => $cart->city,
                'postal_code' => $cart->postal_code,
                'country' => $cart->country,
                'shipping_provider_id' => $cart->shipping_provider_id,
                'shipping_cost' => $shippingCost,
                'payment_method' => $request->input('payment_method'), // This will now be uppercase from validation
                'payment_status' => $request->input('payment_method') === 'COD' ? 'pending' : 'paid',
                'status' => 'new',
                'total_amount' => $totalAmount,
            ]);

            // Add order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $cartItem->product_id,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity
                ]);
                
                // Decrease product stock quantity
                $product = $cartItem->product;
                $product->stock_quantity -= $cartItem->quantity;
                $product->save();
            }

            // Clear cart
            $cart->items()->delete();
            
            // Clear checkout data in cart but keep the cart itself
            $cart->update([
                'email' => null,
                'phone' => null,
                'newsletter' => null,
                'first_name' => null,
                'last_name' => null,
                'address_line1' => null,
                'address_line2' => null,
                'city' => null,
                'postal_code' => null,
                'country' => null,
                'shipping_provider_id' => null,
                'payment_method' => null,
            ]);
            
            // Clear checkout session data
            Session::forget('checkout');
            
            // Store order ID in session for retrieval on success page
            Session::put('order_id', $order->order_id);
            
            DB::commit();
            
            // In a real application, you would send an order confirmation email here
            
            return redirect()->route('cart.success')->with('success', 'Vaša objednávka bola úspešne vytvorená!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Vyskytla sa chyba pri spracovaní vašej objednávky. Prosím, skúste znova. ' . $e->getMessage());
        }
    }

    /**
     * Show order success page
     */
    public function success()
    {
        // If there's a success message with an order ID, get the order
        if (session()->has('order_id')) {
            $order = Order::with('shippingProvider')->find(session('order_id'));
            return view('cart.success', compact('order'));
        }
        
        // Otherwise, if the user is logged in, get their most recent order
        if (Auth::check()) {
            $order = Order::with('shippingProvider')
                ->where('user_id', Auth::id())
                ->latest()
                ->first();
            
            return view('cart.success', compact('order'));
        }
        
        // Default view if no order information is available
        return view('cart.success');
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
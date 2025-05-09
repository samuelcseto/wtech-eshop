<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_id';
    
    protected $fillable = [
        'user_id',
        'session_id',
        // Contact information
        'email',
        'phone',
        'newsletter',
        // Shipping address
        'first_name',
        'last_name',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'country',
        // Shipping method
        'shipping_provider_id',
        // Payment method
        'payment_method',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'newsletter' => 'boolean',
    ];

    /**
     * Get the items in the cart.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    /**
     * Get the user who owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping provider for this cart.
     */
    public function shippingProvider()
    {
        return $this->belongsTo(ShippingProvider::class, 'shipping_provider_id', 'provider_id');
    }

    /**
     * Calculate the total price of all items in the cart.
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Calculate the total number of items in the cart.
     */
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }
}

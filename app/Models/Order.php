<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'order_id';
    
    protected $fillable = [
        'user_id',
        'email',
        'phone',
        'first_name',
        'last_name',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'country',
        'shipping_provider_id',
        'shipping_cost',
        'payment_method',
        'payment_status',
        'status', // Changed from 'order_status' to 'status' to match the database column name
        'total_amount',
    ];
    
    /**
     * Get the user who placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the items in the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    /**
     * Get the shipping provider for this order.
     */
    public function shippingProvider()
    {
        return $this->belongsTo(ShippingProvider::class, 'shipping_provider_id', 'provider_id');
    }
}
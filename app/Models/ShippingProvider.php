<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingProvider extends Model
{
    protected $primaryKey = 'provider_id';
    
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'tracking_url_template',
        'cost_calculation_method',
        'price',
        'country_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
    ];

    /**
     * Get the country associated with the shipping provider.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Scope a query to only include active shipping providers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

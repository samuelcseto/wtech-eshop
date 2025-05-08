<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the shipping providers associated with the country.
     */
    public function shippingProviders()
    {
        return $this->hasMany(ShippingProvider::class, 'country_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'image_url', 'is_primary', 'sort_order', 'alt_text'
    ];
    
    protected $primaryKey = 'image_id';
    public $incrementing = true;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

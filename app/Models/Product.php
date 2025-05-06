<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'stock_quantity', 'is_active', 'image'
    ];
    protected $table = 'products';
    //protected $primaryKey = 'product_id';

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'product_id')->where('is_primary', true);
    }
}

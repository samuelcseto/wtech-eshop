<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_images')->insert([
            [
                'product_id' => 1,
                'image_url' => 'images/products/sedacia-suprava-oslo.jpg',
                'is_primary' => true,
                'sort_order' => 1,
                'alt_text' => 'Sedacia súprava Oslo',
            ],
            [
                'product_id' => 2,
                'image_url' => 'images/products/postel-malmo.jpg',
                'is_primary' => true,
                'sort_order' => 1,
                'alt_text' => 'Posteľ Malmo 180x200',
            ],
            [
                'product_id' => 3,
                'image_url' => 'images/products/stol-bergen.jpg',
                'is_primary' => true,
                'sort_order' => 1,
                'alt_text' => 'Jedálenský stôl Bergen',
            ],
            [
                'product_id' => 4,
                'image_url' => 'images/products/pisaci-stol-alfa.jpg',
                'is_primary' => true,
                'sort_order' => 1,
                'alt_text' => 'Písací stôl Alfa',
            ],
            [
                'product_id' => 5,
                'image_url' => 'images/products/detska-postel-lili.jpg',
                'is_primary' => true,
                'sort_order' => 1,
                'alt_text' => 'Detská posteľ Lili',
            ],
        ]);
        
    }
}

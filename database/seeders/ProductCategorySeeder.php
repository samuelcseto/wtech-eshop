<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Define all product-category associations
        $productCategories = [
            // Original products
            ['product_id' => 1, 'category_id' => 1], // Sedacia súprava Oslo -> Obývačka
            ['product_id' => 2, 'category_id' => 2], // Posteľ Malmo 180x200 -> Spálňa
            ['product_id' => 3, 'category_id' => 3], // Jedálenský stôl Bergen -> Kuchyňa
            ['product_id' => 4, 'category_id' => 4], // Písací stôl Alfa -> Pracovňa
            ['product_id' => 5, 'category_id' => 5], // Detská posteľ Lili -> Detská izba
            
            // New products
            ['product_id' => 6, 'category_id' => 2], // Šatníková skriňa Nordic -> Spálňa
            ['product_id' => 7, 'category_id' => 1], // Komoda Vienna -> Obývačka
            ['product_id' => 8, 'category_id' => 1], // Konferenčný stolík Cube -> Obývačka
            ['product_id' => 9, 'category_id' => 2], // Nočný stolík Milano -> Spálňa
            ['product_id' => 10, 'category_id' => 3], // Jedálenská stolička Enzo -> Kuchyňa
            ['product_id' => 11, 'category_id' => 4], // Kancelárska stolička Ergo -> Pracovňa
            ['product_id' => 12, 'category_id' => 1], // Knižnica Oxford -> Obývačka
            ['product_id' => 13, 'category_id' => 1], // TV stolík Sigma -> Obývačka
            ['product_id' => 14, 'category_id' => 1], // Botník Classic -> Obývačka
            ['product_id' => 15, 'category_id' => 1], // Vešiak Scandi -> Obývačka
            ['product_id' => 16, 'category_id' => 5], // Detský písací stôl Smile -> Detská izba
        ];
        
        // Insert only associations that don't already exist
        foreach ($productCategories as $association) {
            $exists = DB::table('product_category')
                ->where('product_id', $association['product_id'])
                ->where('category_id', $association['category_id'])
                ->exists();
                
            if (!$exists) {
                DB::table('product_category')->insert($association);
            }
        }
    }
}

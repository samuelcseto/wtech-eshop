<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_category')->insert([
            ['product_id' => 1, 'category_id' => 1], // Obývačka
            ['product_id' => 2, 'category_id' => 2], // Spálňa
            ['product_id' => 3, 'category_id' => 3], // Kuchyňa
            ['product_id' => 4, 'category_id' => 4], // Pracovňa
            ['product_id' => 5, 'category_id' => 5], // Detská izba
        ]);
        
    }
}

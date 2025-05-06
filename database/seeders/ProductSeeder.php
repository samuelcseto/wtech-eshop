<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'product_id' => 1,
                'name' => 'Sedacia súprava Oslo',
                'description' => 'Moderná rohová sedačka v sivej farbe s úložným priestorom.',
                'stock_quantity' => 10,
                'price' => 699.99,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'name' => 'Posteľ Malmo 180x200',
                'description' => 'Drevená manželská posteľ s čelom z masívu.',
                'stock_quantity' => 7,
                'price' => 459.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 3,
                'name' => 'Jedálenský stôl Bergen',
                'description' => 'Stôl z dubového dreva pre 6 osôb, ideálny do kuchyne alebo jedálne.',
                'stock_quantity' => 5,
                'price' => 349.99,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 4,
                'name' => 'Písací stôl Alfa',
                'description' => 'Kompaktný pracovný stôl s policami, vhodný do každej pracovne.',
                'stock_quantity' => 15,
                'price' => 129.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 5,
                'name' => 'Detská posteľ Lili',
                'description' => 'Farebná posteľ so zábranou pre bezpečný spánok detí.',
                'stock_quantity' => 12,
                'price' => 199.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Check if products already exist to avoid duplicates
        if (Product::count() > 0) {
            $this->command->info('Products already exist. Skipping product seeding.');
            return;
        }

        $products = [
            [
                'name' => 'Sedacia súprava Oslo',
                'description' => 'Moderná rohová sedačka v sivej farbe s úložným priestorom.',
                'stock_quantity' => 10,
                'price' => 699.99,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Posteľ Malmo 180x200',
                'description' => 'Drevená manželská posteľ s čelom z masívu.',
                'stock_quantity' => 7,
                'price' => 459.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jedálenský stôl Bergen',
                'description' => 'Stôl z dubového dreva pre 6 osôb, ideálny do kuchyne alebo jedálne.',
                'stock_quantity' => 5,
                'price' => 349.99,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Písací stôl Alfa',
                'description' => 'Kompaktný pracovný stôl s policami, vhodný do každej pracovne.',
                'stock_quantity' => 15,
                'price' => 129.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Detská posteľ Lili',
                'description' => 'Farebná posteľ so zábranou pre bezpečný spánok detí.',
                'stock_quantity' => 12,
                'price' => 199.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Šatníková skriňa Nordic',
                'description' => 'Priestranná šatníková skriňa s posuvnými dverami a zrkadlom, ideálna pre moderné spálne.',
                'stock_quantity' => 8,
                'price' => 499.99,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Komoda Vienna',
                'description' => 'Elegantná komoda so 4 zásuvkami v škandinávskom štýle, vyrobená z kvalitného dreva.',
                'stock_quantity' => 14,
                'price' => 279.50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Konferenčný stolík Cube',
                'description' => 'Minimalistický konferenčný stolík s úložným priestorom, perfektný do moderných obývacích izieb.',
                'stock_quantity' => 20,
                'price' => 149.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nočný stolík Milano',
                'description' => 'Praktický nočný stolík s jednou zásuvkou a otvorenou poličkou pre knihy a drobnosti.',
                'stock_quantity' => 25,
                'price' => 89.99,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jedálenská stolička Enzo',
                'description' => 'Moderná čalúnená stolička s kovovými nohami, pohodlná a štýlová pre každý jedálenský stôl.',
                'stock_quantity' => 30,
                'price' => 69.95,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kancelárska stolička Ergo',
                'description' => 'Ergonomická kancelárska stolička s nastaviteľnou výškou a opierkami rúk, ideálna pre dlhé pracovné hodiny.',
                'stock_quantity' => 18,
                'price' => 179.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Knižnica Oxford',
                'description' => 'Vysoká knižnica s piatimi policami, vhodná na uskladnenie kníh a dekoračných predmetov.',
                'stock_quantity' => 9,
                'price' => 219.50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TV stolík Sigma',
                'description' => 'Moderný TV stolík s dvoma zásuvkami a priestorom na multimediálne zariadenia.',
                'stock_quantity' => 12,
                'price' => 159.99,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Botník Classic',
                'description' => 'Praktický botník s tromi policami, ideálny do predsiene alebo šatníka.',
                'stock_quantity' => 15,
                'price' => 119.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vešiak Scandi',
                'description' => 'Stojanový vešiak v škandinávskom štýle vyrobený z kvalitného dreva a kovu.',
                'stock_quantity' => 22,
                'price' => 59.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Detský písací stôl Smile',
                'description' => 'Farebný písací stôl s regulovateľnou výškou, ideálny pre rastúce deti do detskej izby.',
                'stock_quantity' => 10,
                'price' => 139.90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        
        // Insert products
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

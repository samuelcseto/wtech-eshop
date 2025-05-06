<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Obývačka', 'description' => 'Nábytok a doplnky do obývacej izby', 'image_url' => 'images/categories/obyvacka.jpg'],
            ['name' => 'Spálňa', 'description' => 'Postele, skrine a nočné stolíky', 'image_url' => 'images/categories/spalna.jpg'],
            ['name' => 'Kuchyňa', 'description' => 'Jedálenské stoly, stoličky a úložné riešenia', 'image_url' => 'images/categories/kuchyna.jpg'],
            ['name' => 'Pracovňa', 'description' => 'Kancelárske stoly a stoličky', 'image-url' => 'images/categories/pracovna.jpg'],
            ['name' => 'Detská izba', 'description' => 'Nábytok pre deti a tínedžerov', 'image-url' => 'images/categories/detska-izba.jpg'],
        ]);
    }
}

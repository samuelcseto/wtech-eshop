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
            ['name' => 'Obývačka', 'description' => 'Nábytok a doplnky do obývacej izby'],
            ['name' => 'Spálňa', 'description' => 'Postele, skrine a nočné stolíky'],
            ['name' => 'Kuchyňa', 'description' => 'Jedálenské stoly, stoličky a úložné riešenia'],
            ['name' => 'Pracovňa', 'description' => 'Kancelárske stoly a stoličky'],
            ['name' => 'Detská izba', 'description' => 'Nábytok pre deti a tínedžerov'],
        ]);
    }
}

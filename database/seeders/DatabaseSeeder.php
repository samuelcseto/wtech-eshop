<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CountryAndShippingProviderSeeder::class, // Add countries and shipping providers first
            CategorySeeder::class,
            ProductSeeder::class,
            ProductCategorySeeder::class,
            ProductImageSeeder::class,
            AdminUserSeeder::class, // Add the admin user seeder
        ]);

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the admin already exists to avoid duplicates
        if (User::where('email', 'admin@example.com')->exists()) {
            $this->command->info('Admin user already exists. Skipping admin user creation.');
            return;
        }
        
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);
        
        $this->command->info('Admin user created successfully!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        // Create regular user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        // Create additional sample users for testing search/filter
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'is_admin' => false,
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Bob Johnson',
            'email' => 'bob.johnson@example.com',
            'is_admin' => false,
        ]);

        // Create more users for pagination testing
        User::factory(15)->create();
    }
}

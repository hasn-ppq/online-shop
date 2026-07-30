<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Additional test user requested
        User::updateOrCreate([
            'email' => 'second@example.com',
        ], [
            'name' => 'Second User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create sample categories and products if none exist
        if (Category::count() === 0) {
            Category::factory()->count(3)->create();
        }

        if (Product::count() === 0) {
            $categoryIds = Category::all()->pluck('id')->toArray();
            for ($i = 0; $i < 10; $i++) {
                Product::factory()->create([
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                ]);
            }
        }
    }
}

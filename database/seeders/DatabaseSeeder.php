<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@ramarama.co'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => \Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Sample Products
        $products = [
            [
                'name' => 'Black Hoodie',
                'description' => 'A comfortable black hoodie for everyday wear.',
                'price' => 89.90,
                'category' => 'Hoodies',
                'stock_small' => 10,
                'stock_medium' => 20,
                'stock_large' => 15,
                'stock_xl' => 5,
                'stock_2xl' => 2,
                'images' => ['images/bf.jpg', 'images/bf1.jpg'],
            ],
            [
                'name' => 'White T-Shirt',
                'description' => 'Premium white t-shirt with minimalist design.',
                'price' => 45.00,
                'category' => 'Tops',
                'stock_small' => 50,
                'stock_medium' => 60,
                'stock_large' => 40,
                'stock_xl' => 20,
                'stock_2xl' => 10,
                'images' => ['images/bf2.jpg'],
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}

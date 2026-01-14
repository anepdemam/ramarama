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
            ['email' => 'admin@s.u'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => \Hash::make('anep123'),
                'role' => 'admin',
            ]
        );

        // Regular User
        User::updateOrCreate(
            ['email' => 's@s.u'],
            [
                'name' => 'User',
                'username' => 'user',
                'password' => \Hash::make('anep123'),
                'role' => 'customer',
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
                'images' => ['images/hero/bf.jpg', 'images/hero/bf1.jpg'],
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
                'images' => ['images/hero/bf2.jpg'],
            ],
            [
                'name' => 'Oversized Graphic Tee',
                'description' => 'Heavyweight cotton tee with bold back print.',
                'price' => 55.00,
                'category' => 'Tops',
                'stock_small' => 25,
                'stock_medium' => 30,
                'stock_large' => 25,
                'stock_xl' => 10,
                'stock_2xl' => 5,
                'images' => ['images/hero/bf.jpg'],
            ],
            [
                'name' => 'Cargo Joggers',
                'description' => 'Functional cargo joggers with multiple pockets.',
                'price' => 79.00,
                'category' => 'Bottoms',
                'stock_small' => 15,
                'stock_medium' => 25,
                'stock_large' => 20,
                'stock_xl' => 10,
                'stock_2xl' => 5,
                'images' => ['images/hero/bf2.jpg'],
            ],
            [
                'name' => 'Denim Jacket',
                'description' => 'Classic denim jacket with a modern distressed look.',
                'price' => 120.00,
                'category' => 'Outerwear',
                'stock_small' => 10,
                'stock_medium' => 15,
                'stock_large' => 10,
                'stock_xl' => 5,
                'stock_2xl' => 2,
                'images' => ['images/hero/bf.jpg'],
            ],
            [
                'name' => 'Streetwear Cap',
                'description' => 'Adjustable cap with embroidered logo.',
                'price' => 35.00,
                'category' => 'Accessories',
                'stock_small' => 0, // OS
                'stock_medium' => 50, // Treat M as one size for simplicity or adjust logic
                'stock_large' => 0,
                'stock_xl' => 0,
                'stock_2xl' => 0,
                'images' => ['images/hero/bf2.jpg'],
            ],
            [
                'name' => 'Track Pants',
                'description' => 'Lightweight track pants for athletic or casual wear.',
                'price' => 65.00,
                'category' => 'Bottoms',
                'stock_small' => 20,
                'stock_medium' => 30,
                'stock_large' => 25,
                'stock_xl' => 15,
                'stock_2xl' => 5,
                'images' => ['images/hero/bf.jpg'],
            ],
            [
                'name' => 'Puffer Vest',
                'description' => 'Warm and stylish puffer vest for layering.',
                'price' => 95.00,
                'category' => 'Outerwear',
                'stock_small' => 10,
                'stock_medium' => 20,
                'stock_large' => 15,
                'stock_xl' => 8,
                'stock_2xl' => 4,
                'images' => ['images/hero/bf2.jpg'],
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}

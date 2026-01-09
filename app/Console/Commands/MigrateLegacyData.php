<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-legacy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from legacy_ tables to new tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration...');

        // Users
        $legacyUsers = \DB::table('legacy_users')->get();
        foreach ($legacyUsers as $user) {
            \App\Models\User::updateOrCreate(
                ['email' => $user->email],
                [
                    'username' => $user->username,
                    'password' => $user->password,
                    'role' => $user->role ?? 'customer',
                    'name' => $user->username, // Default name to username
                ]
            );
        }
        $this->info('Users migrated.');

        // Products
        $legacyProducts = \DB::table('legacy_products')->get();
        foreach ($legacyProducts as $product) {
            $images = [];
            if (!empty($product->image1))
                $images[] = 'images/' . $product->image1;
            if (!empty($product->image2))
                $images[] = 'images/' . $product->image2;

            \App\Models\Product::updateOrCreate(
                ['name' => $product->name],
                [
                    'description' => $product->description,
                    'price' => $product->price,
                    'category' => $product->category,
                    'stock_small' => $product->stock_small,
                    'stock_medium' => $product->stock_medium,
                    'stock_large' => $product->stock_large,
                    'stock_xl' => $product->stock_xl,
                    'stock_2xl' => $product->stock_2xl,
                    'images' => $images,
                ]
            );
        }
        $this->info('Products migrated.');

        $this->info('Migration completed successfully!');
    }
}

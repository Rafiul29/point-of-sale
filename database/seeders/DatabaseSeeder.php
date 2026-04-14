<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        // Cashier User
        User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'password' => Hash::make('password'),
            'role' => 'Cashier',
        ]);

        // Category
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic items and gadgets',
            'status' => true,
        ]);

        // Supplier
        $supplier = Supplier::create([
            'name' => 'Main Supplier Co.',
            'email' => 'supplier@example.com',
            'phone' => '1234567890',
            'address' => '123 Supplier St, Tech City',
        ]);

        // 5 Sample Products
        $products = [
            ['name' => 'Laptop', 'barcode' => 'PROD001', 'purchase_price' => 800, 'selling_price' => 1200, 'stock_quantity' => 20, 'reorder_level' => 5],
            ['name' => 'Smartphone', 'barcode' => 'PROD002', 'purchase_price' => 400, 'selling_price' => 600, 'stock_quantity' => 50, 'reorder_level' => 10],
            ['name' => 'Headphones', 'barcode' => 'PROD003', 'purchase_price' => 50, 'selling_price' => 80, 'stock_quantity' => 100, 'reorder_level' => 15],
            ['name' => 'Monitor', 'barcode' => 'PROD004', 'purchase_price' => 150, 'selling_price' => 250, 'stock_quantity' => 30, 'reorder_level' => 5],
            ['name' => 'Keyboard', 'barcode' => 'PROD005', 'purchase_price' => 30, 'selling_price' => 50, 'stock_quantity' => 80, 'reorder_level' => 10],
        ];



        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);

        // foreach ($products as $productData) {
        //     Product::create(array_merge($productData, [
        //         'category_id' => $category->id,
        //         'supplier_id' => $supplier->id,
        //     ]));
        // }
    }
}

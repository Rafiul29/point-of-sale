<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fashion',
            'Home Appliances',
            'Computing',
            'Health & Beauty'
        ];

        foreach ($categories as $categoryName) {
            $category = \App\Models\Category::create([
                'name' => $categoryName,
                'slug' => str($categoryName)->slug(),
                'description' => 'Description for ' . $categoryName,
                'status' => true,
            ]);

            // Create subcategories
            \App\Models\Category::factory()->count(3)->create([
                'parent_id' => $category->id,
            ]);
        }
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $category = Category::whereNull('parent_id')->inRandomOrder()->first() ?? Category::factory()->create();
        $subcategory = Category::where('parent_id', $category->id)->inRandomOrder()->first();
        
        $purchase_price = $this->faker->randomFloat(2, 5, 100);
        
        return [
            'name' => $this->faker->unique()->words(3, true),
            'barcode' => $this->faker->unique()->ean13(),
            'category_id' => $category->id,
            'subcategory_id' => $subcategory ? $subcategory->id : null,
            'supplier_id' => Supplier::inRandomOrder()->first()->id ?? Supplier::factory()->create()->id,
            'purchase_price' => $purchase_price,
            'selling_price' => $purchase_price * $this->faker->randomFloat(2, 1.2, 2.0),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'reorder_level' => $this->faker->numberBetween(5, 20),
            'image' => null,
        ];
    }
}

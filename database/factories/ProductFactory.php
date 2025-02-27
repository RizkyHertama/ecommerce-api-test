<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            ['name' => 'iPhone 13', 'description' => 'Smartphone Apple dengan A15 Bionic', 'price' => 14000000, 'stock' => 10],
            ['name' => 'Samsung Galaxy S22', 'description' => 'Smartphone Samsung dengan Snapdragon 8 Gen 1', 'price' => 12000000, 'stock' => 15],
            ['name' => 'Xiaomi 12', 'description' => 'Smartphone Xiaomi dengan Snapdragon 8 Gen 1', 'price' => 9000000, 'stock' => 20],
            ['name' => 'Oppo Find X5', 'description' => 'Smartphone Oppo dengan kamera premium', 'price' => 11000000, 'stock' => 12],
            ['name' => 'Realme GT Neo 3', 'description' => 'Smartphone Realme dengan performa gaming', 'price' => 7500000, 'stock' => 18],
        ];

        static $index = 0;

        return $products[$index++ % count($products)];
    }

}

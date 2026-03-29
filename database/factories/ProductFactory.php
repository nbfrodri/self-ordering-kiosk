<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $products = [
            'Classic Burger',
            'Double Cheeseburger',
            'Bacon BBQ Burger',
            'Veggie Burger',
            'Chicken Burger',
            'French Fries',
            'Onion Rings',
            'Chicken Nuggets',
            'Mozzarella Sticks',
            'Cola',
            'Lemonade',
            'Milkshake',
            'Ice Cream Sundae',
            'Apple Pie',
        ];

        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->randomElement($products),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1.99, 14.99),
            'image_url' => null,
            'is_available' => true,
            'preparation_time_minutes' => $this->faker->numberBetween(3, 15),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(['is_available' => false]);
    }
}

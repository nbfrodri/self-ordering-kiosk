<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = ['Burgers', 'Sides', 'Drinks', 'Desserts', 'Combos', 'Salads', 'Wraps', 'Breakfast'];

        return [
            'name' => $this->faker->unique()->randomElement($categories),
            'description' => $this->faker->sentence(),
            'image_url' => null,
            'display_order' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}

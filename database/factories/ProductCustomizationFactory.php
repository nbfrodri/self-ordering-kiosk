<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCustomization;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCustomizationFactory extends Factory
{
    protected $model = ProductCustomization::class;

    public function definition(): array
    {
        $customizations = [
            ['name' => 'No Onion',        'type' => 'remove', 'price_modifier' => 0.00],
            ['name' => 'No Pickles',      'type' => 'remove', 'price_modifier' => 0.00],
            ['name' => 'No Lettuce',      'type' => 'remove', 'price_modifier' => 0.00],
            ['name' => 'Extra Cheese',    'type' => 'add',    'price_modifier' => 1.00],
            ['name' => 'Extra Bacon',     'type' => 'add',    'price_modifier' => 1.50],
            ['name' => 'Gluten-Free Bun', 'type' => 'add',    'price_modifier' => 2.00],
            ['name' => 'Large Size',      'type' => 'size',   'price_modifier' => 1.50],
            ['name' => 'Extra Sauce',     'type' => 'add',    'price_modifier' => 0.50],
            ['name' => 'No Ice',          'type' => 'remove', 'price_modifier' => 0.00],
        ];

        $choice = $this->faker->randomElement($customizations);

        return [
            'product_id' => Product::factory(),
            'name' => $choice['name'],
            'type' => $choice['type'],
            'price_modifier' => $choice['price_modifier'],
            'is_available' => true,
        ];
    }

    public function unavailable(): static
    {
        return $this->state(['is_available' => false]);
    }
}

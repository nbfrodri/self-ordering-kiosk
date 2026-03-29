<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCustomization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------
        // BURGERS
        // ---------------------------------------------------------------
        $burgers = Category::create([
            'name' => 'Burgers',
            'description' => 'Juicy, flame-grilled burgers made with fresh ingredients.',
            'display_order' => 1,
            'is_active' => true,
        ]);

        $burgerProducts = [
            ['name' => 'Classic Burger',       'description' => 'A timeless classic with lettuce, tomato, and our special sauce.', 'price' => 5.99,  'preparation_time_minutes' => 7],
            ['name' => 'Double Cheeseburger',   'description' => 'Two beef patties, double the cheese, double the pleasure.',        'price' => 8.49,  'preparation_time_minutes' => 8],
            ['name' => 'Bacon BBQ Burger',      'description' => 'Smoky bacon, cheddar, and tangy BBQ sauce.',                       'price' => 9.99,  'preparation_time_minutes' => 9],
            ['name' => 'Veggie Burger',         'description' => 'A hearty plant-based patty with fresh toppings.',                  'price' => 7.49,  'preparation_time_minutes' => 7],
            ['name' => 'Chicken Burger',        'description' => 'Crispy fried chicken breast with coleslaw and pickles.',           'price' => 7.99,  'preparation_time_minutes' => 8],
        ];

        $burgerCustomizations = [
            ['name' => 'No Onion',        'type' => 'remove', 'price_modifier' => 0.00],
            ['name' => 'No Pickles',      'type' => 'remove', 'price_modifier' => 0.00],
            ['name' => 'No Lettuce',      'type' => 'remove', 'price_modifier' => 0.00],
            ['name' => 'Extra Cheese',    'type' => 'add',    'price_modifier' => 1.00],
            ['name' => 'Extra Bacon',     'type' => 'add',    'price_modifier' => 1.50],
            ['name' => 'Gluten-Free Bun', 'type' => 'add',    'price_modifier' => 2.00],
        ];

        foreach ($burgerProducts as $data) {
            $product = Product::create(array_merge($data, ['category_id' => $burgers->id, 'is_available' => true]));
            foreach ($burgerCustomizations as $custom) {
                ProductCustomization::create(array_merge($custom, ['product_id' => $product->id, 'is_available' => true]));
            }
        }

        // ---------------------------------------------------------------
        // SIDES
        // ---------------------------------------------------------------
        $sides = Category::create([
            'name' => 'Sides',
            'description' => 'Perfect companions for any meal.',
            'display_order' => 2,
            'is_active' => true,
        ]);

        $sideProducts = [
            ['name' => 'French Fries',        'description' => 'Golden, crispy fries seasoned to perfection.',         'price' => 3.49, 'preparation_time_minutes' => 5],
            ['name' => 'Onion Rings',          'description' => 'Beer-battered onion rings, perfectly crispy.',         'price' => 4.49, 'preparation_time_minutes' => 5],
            ['name' => 'Chicken Nuggets 6pc',  'description' => 'Six tender, golden chicken nuggets.',                  'price' => 5.99, 'preparation_time_minutes' => 6],
            ['name' => 'Mozzarella Sticks',    'description' => 'Breaded mozzarella with marinara dipping sauce.',      'price' => 4.99, 'preparation_time_minutes' => 5],
            ['name' => 'Coleslaw',             'description' => 'Creamy homestyle coleslaw.',                           'price' => 2.99, 'preparation_time_minutes' => 2],
        ];

        $sideCustomizations = [
            ['name' => 'Large Size',   'type' => 'size', 'price_modifier' => 1.50],
            ['name' => 'Extra Sauce',  'type' => 'add',  'price_modifier' => 0.50],
        ];

        foreach ($sideProducts as $data) {
            $product = Product::create(array_merge($data, ['category_id' => $sides->id, 'is_available' => true]));
            foreach ($sideCustomizations as $custom) {
                ProductCustomization::create(array_merge($custom, ['product_id' => $product->id, 'is_available' => true]));
            }
        }

        // ---------------------------------------------------------------
        // DRINKS
        // ---------------------------------------------------------------
        $drinks = Category::create([
            'name' => 'Drinks',
            'description' => 'Refreshing beverages to quench your thirst.',
            'display_order' => 3,
            'is_active' => true,
        ]);

        $drinkProducts = [
            ['name' => 'Cola',       'description' => 'Classic cola, ice cold.',                     'price' => 2.49, 'preparation_time_minutes' => 1],
            ['name' => 'Lemonade',   'description' => 'Freshly squeezed lemonade.',                  'price' => 2.99, 'preparation_time_minutes' => 2],
            ['name' => 'Iced Tea',   'description' => 'Brewed iced tea, lightly sweetened.',          'price' => 2.49, 'preparation_time_minutes' => 1],
            ['name' => 'Milkshake',  'description' => 'Thick and creamy milkshake in three flavors.', 'price' => 4.99, 'preparation_time_minutes' => 4],
            ['name' => 'Water',      'description' => 'Still or sparkling mineral water.',            'price' => 1.49, 'preparation_time_minutes' => 1],
        ];

        $drinkCustomizations = [
            ['name' => 'Large Size', 'type' => 'size',   'price_modifier' => 1.00],
            ['name' => 'No Ice',     'type' => 'remove',  'price_modifier' => 0.00],
        ];

        foreach ($drinkProducts as $data) {
            $product = Product::create(array_merge($data, ['category_id' => $drinks->id, 'is_available' => true]));
            foreach ($drinkCustomizations as $custom) {
                ProductCustomization::create(array_merge($custom, ['product_id' => $product->id, 'is_available' => true]));
            }
        }

        // ---------------------------------------------------------------
        // DESSERTS
        // ---------------------------------------------------------------
        $desserts = Category::create([
            'name' => 'Desserts',
            'description' => 'Sweet treats to finish your meal.',
            'display_order' => 4,
            'is_active' => true,
        ]);

        $dessertProducts = [
            ['name' => 'Ice Cream Sundae', 'description' => 'Vanilla ice cream with choice of topping.',     'price' => 3.99, 'preparation_time_minutes' => 3],
            ['name' => 'Apple Pie',        'description' => 'Warm apple pie with cinnamon glaze.',           'price' => 2.99, 'preparation_time_minutes' => 3],
            ['name' => 'Brownie',          'description' => 'Rich chocolate brownie, served warm.',          'price' => 3.49, 'preparation_time_minutes' => 2],
            ['name' => 'Churros',          'description' => 'Crispy churros dusted with cinnamon sugar.',    'price' => 3.99, 'preparation_time_minutes' => 4],
        ];

        foreach ($dessertProducts as $data) {
            Product::create(array_merge($data, ['category_id' => $desserts->id, 'is_available' => true]));
        }

        // ---------------------------------------------------------------
        // COMBOS
        // ---------------------------------------------------------------
        $combos = Category::create([
            'name' => 'Combos',
            'description' => 'Complete meals at a great value.',
            'display_order' => 5,
            'is_active' => true,
        ]);

        $comboProducts = [
            ['name' => 'Classic Combo',  'description' => 'Classic Burger + Fries + Drink.',                    'price' => 9.99,  'preparation_time_minutes' => 10],
            ['name' => 'Double Combo',   'description' => 'Double Cheeseburger + Large Fries + Large Drink.',   'price' => 12.99, 'preparation_time_minutes' => 12],
            ['name' => 'Chicken Combo',  'description' => 'Chicken Burger + Fries + Drink.',                    'price' => 11.49, 'preparation_time_minutes' => 10],
            ['name' => 'Kids Combo',     'description' => 'Kids Burger + Small Fries + Kids Drink + Toy.',      'price' => 6.99,  'preparation_time_minutes' => 8],
        ];

        foreach ($comboProducts as $data) {
            Product::create(array_merge($data, ['category_id' => $combos->id, 'is_available' => true]));
        }
    }
}

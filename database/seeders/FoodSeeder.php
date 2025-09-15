<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu có
        DB::table('foods')->truncate();

        // Lấy các category để lấy ID
        $burgerCategory = Category::where('slug', 'burger')->first();
        $pizzaCategory = Category::where('slug', 'pizza')->first();
        $sushiCategory = Category::where('slug', 'sushi')->first();
        $cakeCategory = Category::where('slug', 'cake')->first();
        $drinksCategory = Category::where('slug', 'drinks')->first();
        $saladCategory = Category::where('slug', 'salad')->first();

        $foods = [
            // Salad foods
            [
                'name' => 'Fried Egg',
                'image' => 'foods/fried_egg.png',
                'price' => 15.06,
                'rating' => 4.3,
                'review_count' => 2005,
                'description' => 'Delicious fried egg with special sauce',
                'category_id' => $saladCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Mixed Vegetable',
                'image' => 'foods/mixed_vegetable.png',
                'price' => 17.03,
                'rating' => 4.3,
                'review_count' => 100,
                'description' => 'Fresh mixed vegetables with special dressing',
                'category_id' => $saladCategory->id,
                'is_popular' => false,
            ],

            // Burger foods
            [
                'name' => 'Classic Burger',
                'image' => 'foods/burger.png',
                'price' => 5.99,
                'rating' => 4.9,
                'review_count' => 1500,
                'description' => 'Juicy beef burger with cheese and vegetables',
                'category_id' => $burgerCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Cheese Burger',
                'image' => 'foods/cheese_burger.png',
                'price' => 6.99,
                'rating' => 4.7,
                'review_count' => 1200,
                'description' => 'Burger with extra cheese and special sauce',
                'category_id' => $burgerCategory->id,
                'is_popular' => true,
            ],

            // Pizza foods
            [
                'name' => 'Via Napoli Pizzeria',
                'image' => 'foods/pizza.png',
                'price' => 8.5,
                'rating' => 4.8,
                'review_count' => 1200,
                'description' => 'Delicious pizza with various toppings',
                'category_id' => $pizzaCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Margherita Pizza',
                'image' => 'foods/margherita.png',
                'price' => 7.5,
                'rating' => 4.6,
                'review_count' => 900,
                'description' => 'Classic pizza with tomato and mozzarella',
                'category_id' => $pizzaCategory->id,
                'is_popular' => false,
            ],

            // Sushi foods
            [
                'name' => 'Salmon Sushi',
                'image' => 'foods/sushi.png',
                'price' => 12.99,
                'rating' => 4.5,
                'review_count' => 800,
                'description' => 'Fresh salmon sushi with rice and seaweed',
                'category_id' => $sushiCategory->id,
                'is_popular' => true,
            ],

            // Cake foods
            [
                'name' => 'Chocolate Cake',
                'image' => 'foods/chocolate_cake.png',
                'price' => 4.99,
                'rating' => 4.8,
                'review_count' => 1300,
                'description' => 'Rich chocolate cake with cream frosting',
                'category_id' => $cakeCategory->id,
                'is_popular' => false,
            ],

            // Drinks foods
            [
                'name' => 'Fresh Orange Juice',
                'image' => 'foods/orange_juice.png',
                'price' => 3.5,
                'rating' => 4.4,
                'review_count' => 600,
                'description' => 'Freshly squeezed orange juice',
                'category_id' => $drinksCategory->id,
                'is_popular' => false,
            ],
            [
                'name' => 'Iced Coffee',
                'image' => 'foods/iced_coffee.png',
                'price' => 4.0,
                'rating' => 4.6,
                'review_count' => 750,
                'description' => 'Cold coffee with milk and ice',
                'category_id' => $drinksCategory->id,
                'is_popular' => true,
            ],
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }

        $this->command->info('Foods seeded successfully!');
        $this->command->info('Total foods: ' . count($foods));
    }
}
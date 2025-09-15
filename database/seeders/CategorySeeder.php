<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Burger', 'slug' => 'burger'],
            ['name' => 'Pizza', 'slug' => 'pizza'],
            ['name' => 'Sushi', 'slug' => 'sushi'],
            ['name' => 'Cake', 'slug' => 'cake'],
            ['name' => 'Drinks', 'slug' => 'drinks'],
            ['name' => 'Salad', 'slug' => 'salad'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

// database/seeders/DatabaseSeeder.php

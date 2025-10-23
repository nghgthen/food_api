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
        // Xóa dữ liệu cũ
       DB::table('foods')->delete();


        // Lấy ID của các danh mục
        $burgerCategory = Category::where('slug', 'burger')->first();
        $pizzaCategory = Category::where('slug', 'pizza')->first();
        $sushiCategory = Category::where('slug', 'sushi')->first();
        $cakeCategory = Category::where('slug', 'cake')->first();
        $drinksCategory = Category::where('slug', 'drinks')->first();
        $saladCategory = Category::where('slug', 'salad')->first();

        $foods = [
            // Salad
            [
                'name' => 'Trứng chiên đặc biệt',
                'image' => 'foods/fried_egg.png',
                'price' => 35000,
                'rating' => 4.3,
                'review_count' => 2005,
                'description' => 'Trứng chiên vàng giòn, ăn kèm nước sốt đặc biệt hấp dẫn.',
                'category_id' => $saladCategory->id,
                'is_popular' => false,
            ],
            [
                'name' => 'Rau trộn tươi ngon',
                'image' => 'foods/mixed_vegetable.png',
                'price' => 42000,
                'rating' => 4.3,
                'review_count' => 100,
                'description' => 'Rau củ tươi xanh, trộn cùng sốt mè rang đặc trưng.',
                'category_id' => $saladCategory->id,
                'is_popular' => false,
            ],

            // Burger
            [
                'name' => 'Burger bò cổ điển',
                'image' => 'foods/burger.png',
                'price' => 65000,
                'rating' => 4.9,
                'review_count' => 1500,
                'description' => 'Bánh burger nhân bò nướng mọng nước, kèm phô mai và rau tươi.',
                'category_id' => $burgerCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Burger phô mai đặc biệt',
                'image' => 'foods/cheese_burger.png',
                'price' => 69000,
                'rating' => 4.7,
                'review_count' => 1200,
                'description' => 'Burger nhân bò với lớp phô mai tan chảy và sốt đậm đà.',
                'category_id' => $burgerCategory->id,
                'is_popular' => true,
            ],

            // Pizza
            [
                'name' => 'Pizza hải sản Napoli',
                'image' => 'foods/pizza.png',
                'price' => 95000,
                'rating' => 4.8,
                'review_count' => 1200,
                'description' => 'Pizza hải sản thơm ngon với phô mai mozzarella béo ngậy.',
                'category_id' => $pizzaCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Pizza Margherita truyền thống',
                'image' => 'foods/margherita.png',
                'price' => 89000,
                'rating' => 4.6,
                'review_count' => 900,
                'description' => 'Pizza cà chua và phô mai đơn giản nhưng đậm vị Ý.',
                'category_id' => $pizzaCategory->id,
                'is_popular' => false,
            ],

            // Sushi
            [
                'name' => 'Sushi cá hồi tươi',
                'image' => 'foods/sushi.png',
                'price' => 120000,
                'rating' => 4.5,
                'review_count' => 800,
                'description' => 'Sushi cá hồi tươi ngon, kết hợp cơm giấm và rong biển thơm.',
                'category_id' => $sushiCategory->id,
                'is_popular' => false,
            ],

            // Bánh ngọt
            [
                'name' => 'Bánh kem socola',
                'image' => 'foods/chocolate_cake.png',
                'price' => 45000,
                'rating' => 4.8,
                'review_count' => 1300,
                'description' => 'Bánh socola mềm mịn, phủ kem tươi ngọt ngào.',
                'category_id' => $cakeCategory->id,
                'is_popular' => true,
            ],

            // Đồ uống
            [
                'name' => 'Nước cam ép tươi',
                'image' => 'foods/orange_juice.png',
                'price' => 30000,
                'rating' => 4.4,
                'review_count' => 600,
                'description' => 'Nước cam tươi nguyên chất, giàu vitamin C và sảng khoái.',
                'category_id' => $drinksCategory->id,
                'is_popular' => false,
            ],
            [
                'name' => 'Cà phê sữa đá',
                'image' => 'foods/iced_coffee.png',
                'price' => 35000,
                'rating' => 4.6,
                'review_count' => 750,
                'description' => 'Cà phê rang xay đậm vị, pha cùng sữa đặc và đá mát lạnh.',
                'category_id' => $drinksCategory->id,
                'is_popular' => false,
            ],
            // =======================================================
// MÓN ĂN KÈM (AI GỢI Ý)
// =======================================================
            [
                'name' => 'Khoai tây chiên',
                'image' => 'foods/fries.png',
                'price' => 25000,
                'rating' => 4.6,
                'review_count' => 850,
                'description' => 'Món ăn kèm giòn rụm, hợp với burger và pizza.',
                'category_id' => $burgerCategory->id, // vì hợp burger
                'is_popular' =>false,
            ],
            [
                'name' => 'Coca-Cola',
                'image' => 'foods/coca.png',
                'price' => 20000,
                'rating' => 4.7,
                'review_count' => 1200,
                'description' => 'Đồ uống có gas mát lạnh, hợp với burger, pizza và món chiên.',
                'category_id' => $drinksCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Trà đào cam sả',
                'image' => 'foods/peach_tea.png',
                'price' => 30000,
                'rating' => 4.5,
                'review_count' => 900,
                'description' => 'Đồ uống trái cây mát lạnh, hợp với salad và bánh ngọt.',
                'category_id' => $drinksCategory->id,
                'is_popular' => false,
            ],
            [
                'name' => 'Súp kem nấm',
                'image' => 'foods/mushroom_soup.png',
                'price' => 45000,
                'rating' => 4.4,
                'review_count' => 700,
                'description' => 'Món khai vị nhẹ nhàng, hợp với pizza hoặc burger.',
                'category_id' => $soupCategory->id ?? $saladCategory->id, // nếu chưa có category soup
                'is_popular' => false,
            ],
            [
                'name' => 'Gỏi cuốn tôm thịt',
                'image' => 'foods/spring_roll.png',
                'price' => 35000,
                'rating' => 4.8,
                'review_count' => 1000,
                'description' => 'Món Việt thanh mát, hợp với salad và món chiên.',
                'category_id' => $saladCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Sashimi cá ngừ',
                'image' => 'foods/tuna_sashimi.png',
                'price' => 120000,
                'rating' => 4.7,
                'review_count' => 650,
                'description' => 'Món ăn Nhật tươi ngon, hợp với sushi và cơm cuộn.',
                'category_id' => $sushiCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Mì Ý sốt kem',
                'image' => 'foods/pasta.png',
                'price' => 75000,
                'rating' => 4.6,
                'review_count' => 820,
                'description' => 'Món Âu đặc trưng, hợp với pizza hoặc burger.',
                'category_id' => $pizzaCategory->id,
                'is_popular' => false,
            ],
            [
                'name' => 'Canh khổ qua nhồi thịt',
                'image' => 'foods/bitter_melon_soup.png',
                'price' => 40000,
                'rating' => 4.4,
                'review_count' => 500,
                'description' => 'Món Việt thanh đạm, hợp với trứng chiên và cơm.',
                'category_id' => $saladCategory->id,
                'is_popular' => false,
            ],
            [
                'name' => 'Bánh mì bơ tỏi',
                'image' => 'foods/garlic_bread.png',
                'price' => 30000,
                'rating' => 4.7,
                'review_count' => 1100,
                'description' => 'Món ăn kèm thơm bơ tỏi, hợp với pizza hoặc món Âu.',
                'category_id' => $pizzaCategory->id,
                'is_popular' => true,
            ],
            [
                'name' => 'Salad trộn dầu giấm',
                'image' => 'foods/vinaigrette_salad.png',
                'price' => 38000,
                'rating' => 4.5,
                'review_count' => 740,
                'description' => 'Giúp cân bằng vị béo của burger hoặc đồ chiên.',
                'category_id' => $saladCategory->id,
                'is_popular' => false,
            ],
            [
                'name' => 'Trà sữa trân châu',
                'image' => 'foods/milk_tea.png',
                'price' => 32000,
                'rating' => 4.8,
                'review_count' => 2000,
                'description' => 'Đồ uống ngọt ngào, hợp với bánh ngọt.',
                'category_id' => $drinksCategory->id,
                'is_popular' => true,
            ],

        ];

        foreach ($foods as $food) {
            Food::create($food);
        }

        $this->command->info('Đã seed dữ liệu món ăn thành công!');
        $this->command->info('Tổng số món ăn: ' . count($foods));
    }
}

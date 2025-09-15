<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Lấy danh sách foods với filter
     */
    public function index(Request $request): JsonResponse
    {
        $query = Food::with('category');

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by category slug
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter popular foods
        if ($request->has('popular')) {
            $query->where('is_popular', true)
                  ->orWhere('rating', '>=', 4.5);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->get();
        
        return response()->json($foods);
    }

    /**
     * Lấy thông tin chi tiết food
     */
    public function show(string $id): JsonResponse
    {
        $food = Food::with('category')->find($id);
        
        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }

        return response()->json($food);
    }

    /**
     * Lấy foods theo category
     */
    public function getByCategory(string $categoryId): JsonResponse
    {
        $foods = Food::with('category')
                    ->where('category_id', $categoryId)
                    ->get();
        
        return response()->json($foods);
    }
}
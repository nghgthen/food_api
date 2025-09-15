<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Lấy danh sách tất cả categories
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('foods')->get();
        
        return response()->json($categories);
    }

    /**
     * Lấy thông tin chi tiết một category
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::withCount('foods')->find($id);
        
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    /**
     * Lấy danh sách foods theo category
     */
    public function getFoodsByCategory(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)->first();
        
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $foods = $category->foods()->get();
        
        return response()->json([
            'category' => $category,
            'foods' => $foods
        ]);
    }
}
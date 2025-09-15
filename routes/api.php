<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public API routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories/{slug}/foods', [CategoryController::class, 'getFoodsByCategory']);

Route::get('/foods', [FoodController::class, 'index']);
Route::get('/foods/{id}', [FoodController::class, 'show']);
Route::get('/foods/category/{categoryId}', [FoodController::class, 'getByCategory']);
Route::get('/foods/featured', [FoodController::class, 'featured']);

// Protected routes (require authentication)
Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/update/{foodId}', [CartController::class, 'updateQuantity']);
    Route::delete('/cart/remove/{foodId}', [CartController::class, 'removeFromCart']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
    Route::get('/cart/count', [CartController::class, 'getCartCount']);
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    
    // User profile routes
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/password', [AuthController::class, 'updatePassword']);
});
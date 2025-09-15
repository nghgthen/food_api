<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $cartItems = Cart::with('food')
                ->where('user_id', $user->id)
                ->get();

            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->food->price;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cartItems,
                    'total' => $total
                ],
                'message' => 'Cart retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'food_id' => 'required|exists:foods,id',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $food = Food::findOrFail($request->food_id);

            $cartItem = Cart::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'food_id' => $request->food_id
                ],
                ['quantity' => $request->quantity]
            );

            return response()->json([
                'success' => true,
                'data' => $cartItem->load('food'),
                'message' => 'Item added to cart successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateQuantity(Request $request, $foodId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            if ($request->quantity == 0) {
                Cart::where('user_id', $user->id)
                    ->where('food_id', $foodId)
                    ->delete();
                    
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart successfully'
                ]);
            }

            $cartItem = Cart::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'food_id' => $foodId
                ],
                ['quantity' => $request->quantity]
            );

            return response()->json([
                'success' => true,
                'data' => $cartItem->load('food'),
                'message' => 'Cart updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeFromCart($foodId)
    {
        try {
            $user = Auth::user();

            Cart::where('user_id', $user->id)
                ->where('food_id', $foodId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function clearCart()
    {
        try {
            $user = Auth::user();

            Cart::where('user_id', $user->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCartCount()
    {
        try {
            $user = Auth::user();
            $count = Cart::where('user_id', $user->id)->sum('quantity');

            return response()->json([
                'success' => true,
                'data' => ['count' => $count],
                'message' => 'Cart count retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
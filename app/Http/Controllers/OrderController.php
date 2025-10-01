<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Lấy danh sách tất cả đơn hàng
    public function index()
    {
        $orders = Order::with('items.food')->get();
        return response()->json($orders);
    }

    // Lưu đơn hàng từ Flutter
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:foods,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        // lấy user_id từ token đăng nhập (không cần client gửi)
        $userId = auth()->id();

        $totalAmount = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $totalAmount += $subtotal;

            $itemsData[] = [
                'food_id' => $item['food_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $subtotal,
            ];
        }
        $totalAmount += 0.99;

        $order = Order::create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
        ]);

        $order->items()->createMany($itemsData);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load('items.food')
        ], 201);
    }

    // Lấy chi tiết đơn hàng theo ID
    public function show($id)
    {
        $order = Order::with('items.food')->findOrFail($id);
        return response()->json($order);
    }

    // Lấy danh sách đơn hàng theo user
    public function userOrders($userId)
    {
        $orders = Order::with('items.food')
            ->where('user_id', $userId)
            ->get();

        return response()->json($orders);
    }
}

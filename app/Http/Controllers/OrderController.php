<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $totalAmount += 15000;

        $order = Order::create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'shipped',
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

    // Cập nhật trạng thái đơn hàng (cho người dùng xác nhận đã nhận)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);

        // Kiểm tra quyền: chỉ user sở hữu order mới có thể cập nhật
        if (Auth::id() !== $order->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // User chỉ có thể cập nhật từ 'shipped' hoặc 'processing' sang 'completed'
        if (!in_array($order->status, ['processing', 'shipped'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be updated to completed from current status.'
            ], 422);
        }

        if ($request->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'You can only confirm receipt (set status to completed).'
            ], 422);
        }

        $order->status = $request->status;
        
        // Cập nhật payment_status nếu chưa thanh toán
        if ($order->payment_status === 'unpaid') {
            $order->payment_status = 'paid';
        }
        
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order->load('items.food')
        ]);
    }
}
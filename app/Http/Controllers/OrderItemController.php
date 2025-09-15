<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class OrderItemController extends Controller
{
    /**
     * Display a listing of order items for a specific order.
     */
    public function index(Order $order)
    {
        // Kiểm tra quyền truy cập - user chỉ xem được order items của chính họ, admin xem được tất cả
        if (Auth::id() !== $order->user_id && !Gate::allows('view-any-order')) {
            abort(403, 'Unauthorized action.');
        }

        $items = $order->items()->with('food')->get();
        
        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Store a newly created order item in storage.
     */
    public function store(Request $request, Order $order)
    {
        // Kiểm tra quyền truy cập
        if (!Gate::allows('update-order', $order)) {
            abort(403, 'Unauthorized action.');
        }

        // Chỉ cho phép thêm items vào orders ở trạng thái pending
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Items can only be added to orders with pending status.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $food = Food::find($request->food_id);
        $subtotal = $food->price * $request->quantity;

        // Tạo order item mới
        $orderItem = $order->items()->create([
            'food_id' => $request->food_id,
            'quantity' => $request->quantity,
            'price' => $food->price,
            'subtotal' => $subtotal
        ]);

        // Cập nhật tổng tiền order
        $order->total_amount += $subtotal;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Item added to order successfully',
            'data' => $orderItem->load('food')
        ]);
    }

    /**
     * Display the specified order item.
     */
    public function show(Order $order, OrderItem $item)
    {
        // Kiểm tra quyền truy cập
        if (Auth::id() !== $order->user_id && !Gate::allows('view-any-order')) {
            abort(403, 'Unauthorized action.');
        }

        // Kiểm tra item thuộc về order
        if ($item->order_id !== $order->id) {
            abort(404, 'Item not found in this order.');
        }

        $item->load('food');

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    /**
     * Update the specified order item in storage.
     */
    public function update(Request $request, Order $order, OrderItem $item)
    {
        // Kiểm tra quyền truy cập
        if (!Gate::allows('update-order', $order)) {
            abort(403, 'Unauthorized action.');
        }

        // Kiểm tra item thuộc về order
        if ($item->order_id !== $order->id) {
            abort(404, 'Item not found in this order.');
        }

        // Chỉ cho phép update items trong orders ở trạng thái pending
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Items can only be updated in orders with pending status.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Lưu lại subtotal cũ để điều chỉnh tổng tiền order
        $oldSubtotal = $item->subtotal;

        // Cập nhật quantity và tính subtotal mới
        $item->quantity = $request->quantity;
        $item->subtotal = $item->price * $request->quantity;
        $item->save();

        // Cập nhật tổng tiền order
        $order->total_amount = $order->total_amount - $oldSubtotal + $item->subtotal;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully',
            'data' => $item->load('food')
        ]);
    }

    /**
     * Remove the specified order item from storage.
     */
    public function destroy(Order $order, OrderItem $item)
    {
        // Kiểm tra quyền truy cập
        if (!Gate::allows('update-order', $order)) {
            abort(403, 'Unauthorized action.');
        }

        // Kiểm tra item thuộc về order
        if ($item->order_id !== $order->id) {
            abort(404, 'Item not found in this order.');
        }

        // Chỉ cho phép xóa items từ orders ở trạng thái pending
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Items can only be removed from orders with pending status.'
            ], 422);
        }

        // Cập nhật tổng tiền order
        $order->total_amount -= $item->subtotal;
        $order->save();

        // Xóa item
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from order successfully.'
        ]);
    }

    /**
     * Get order items for authenticated user's order
     */
    public function userOrderItems(Order $order)
    {
        // Kiểm order thuộc về user đang đăng nhập
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $items = $order->items()->with('food')->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
}
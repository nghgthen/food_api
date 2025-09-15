<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::with(['user', 'items'])->latest()->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $this->authorize('create', Order::class);

        $users = User::all();
        return view('orders.create', compact('users'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $order = Order::create($request->all());

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'items.product']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);

        $users = User::all();
        return view('orders.edit', compact('order', 'users'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $order->update($request->all());

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    /**
     * Display orders for the authenticated user.
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('orders.my-orders', compact('orders'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status value'
            ], 422);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $this->authorize('updatePaymentStatus', $order);

        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment status value'
            ], 422);
        }

        $order->payment_status = $request->payment_status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully'
        ]);
    }
}
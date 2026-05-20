<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = Order::with(['user', 'shippingMethod', 'paymentMethod'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load([
            'user', 
            'items.variant.product', 
            'addresses', 
            'shipments', 
            'transactions',
            'paymentMethod',
            'shippingMethod'
        ]);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,completed,cancelled',
            'payment_status' => 'required|string|in:pending,paid,failed,refunded',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('success', __('Order updated successfully.'));
    }
}

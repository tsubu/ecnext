<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyPageController extends Controller
{
    /**
     * Display the user's order history.
     */
    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items', 'items.variant'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Shop/MyPage/Orders', [
            'orders' => $orders
        ]);
    }

    /**
     * Display the details of a specific order.
     */
    public function orderDetail(Order $order)
    {
        // Ensure user owns the order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.variant.product', 'addresses', 'shipments']);

        return Inertia::render('Shop/MyPage/OrderDetail', [
            'order' => $order
        ]);
    }

    /**
     * Display the user profile.
     */
    public function profile()
    {
        return Inertia::render('Shop/MyPage/Profile', [
            'user' => auth()->user()
        ]);
    }
}

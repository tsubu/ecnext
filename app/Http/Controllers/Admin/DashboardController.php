<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_sales' => Order::where('status', '!=', 'cancelled')->sum('total_price'),
            'order_count' => Order::count(),
            'product_count' => Product::count(),
            'audit_logs' => AuditLog::with('auditable')->latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

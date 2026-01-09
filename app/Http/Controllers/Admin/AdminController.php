<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $customerCount = User::where('role', 'customer')->count();
        $totalEarnings = Order::where('status', 'Completed')->sum('total');
        $orderCount = Order::count();

        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact('productCount', 'customerCount', 'totalEarnings', 'orderCount', 'recentOrders'));
    }
}

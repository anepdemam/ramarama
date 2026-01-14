<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function track(Request $request, \App\Services\TrackingService $trackingService)
    {
        $order = null;
        $trackingData = null;

        if ($request->filled('order_id')) {
            $order = Order::with('items.product')->find($request->order_id);
            if (!$order) {
                return back()->with('error', 'Order not found. Please check your Order ID.');
            }

            if ($order->tracking_number) {
                $trackingData = $trackingService->getTrackingStatus($order->tracking_number);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('orders.partials.tracking-status', compact('order', 'trackingData'))->render(),
                'tracking_number' => $order->tracking_number ?? null,
                'status' => $order->status
            ]);
        }

        return view('orders.track', compact('order', 'trackingData'));
    }
}

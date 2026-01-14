<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order, \App\Services\TrackingService $trackingService)
    {
        $request->validate([
            'status' => 'required|string',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $order->update($request->only('status', 'tracking_number'));

        // Register shipment with Tracking.my if status is Shipped and has tracking number
        if ($request->status === 'Shipped' && $request->filled('tracking_number')) {
            // Assuming 'courier' might be determined or defaults to auto-detect by Tracking.my
            // For now passing 'auto' or we could add a courier field later
            $trackingService->registerShipment($request->tracking_number, 'auto', $order->id);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
    }
}

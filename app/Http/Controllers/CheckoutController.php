<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
        ]);

        $cart = Session::get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'Pending',
                'total' => $total,
                'payment_method' => 'FPX Online Banking', // Dummy value for now
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                    'price' => $item['price'],
                ]);

                // Update stock
                $product = Product::find($item['id']);
                $stockField = 'stock_' . strtolower(str_replace(' ', '', $item['size']));
                if ($item['size'] == '2XL')
                    $stockField = 'stock_2xl';

                $product->$stockField -= $item['quantity'];
                $product->save();
            }

            DB::commit();
            Session::forget('cart');

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }
}

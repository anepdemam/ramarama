<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

use App\Models\Voucher;
use Carbon\Carbon;

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

        $voucher = Session::get('voucher');
        $discount = 0;

        if ($voucher) {
            if ($voucher['discount_type'] === 'percentage') {
                $discount = ($total * $voucher['discount_value']) / 100;
                if ($voucher['max_discount'] && $discount > $voucher['max_discount']) {
                    $discount = $voucher['max_discount'];
                }
            } else {
                $discount = $voucher['discount_value'];
            }
        }

        $finalTotal = max(0, $total - $discount);

        return view('checkout.index', compact('cart', 'total', 'discount', 'finalTotal', 'voucher'));
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return back()->with('error', 'Invalid voucher code.');
        }

        if ($voucher->expiration_date && Carbon::parse($voucher->expiration_date)->isPast()) {
            return back()->with('error', 'This voucher has expired.');
        }

        $total = 0;
        foreach (Session::get('cart', []) as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        if ($total < $voucher->min_spend) {
            return back()->with('error', 'Minimum spend of RM' . number_format($voucher->min_spend, 2) . ' required.');
        }

        Session::put('voucher', [
            'code' => $voucher->code,
            'discount_type' => $voucher->discount_type,
            'discount_value' => $voucher->discount_value,
            'max_discount' => $voucher->max_discount,
        ]);

        return back()->with('success', 'Voucher applied successfully!');
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|string|max:20',
            'state' => 'required|string|max:255',
        ]);

        $fullAddress = $request->address_1;
        if ($request->address_2) {
            $fullAddress .= ', ' . $request->address_2;
        }
        $fullAddress .= ', ' . $request->postcode . ' ' . $request->city . ', ' . $request->state;

        $request->merge(['shipping_address' => $fullAddress]);

        $cart = Session::get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $voucher = Session::get('voucher');
        $discount = 0;

        if ($voucher) {
            if ($voucher['discount_type'] === 'percentage') {
                $discount = ($total * $voucher['discount_value']) / 100;
                if (isset($voucher['max_discount']) && $voucher['max_discount'] && $discount > $voucher['max_discount']) {
                    $discount = $voucher['max_discount'];
                }
            } else {
                $discount = $voucher['discount_value'];
            }
        }

        $finalTotal = max(0, $total - $discount);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'Pending',
                'total' => $finalTotal,
                'payment_method' => 'FPX Online Banking',
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'voucher_code' => $voucher ? $voucher['code'] : null,
                'discount' => $discount,
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
            Session::forget('voucher');

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }
}

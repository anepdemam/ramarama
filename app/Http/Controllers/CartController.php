<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = Session::get('cart', []);
        $size = $request->input('size', 'M');
        $quantity = $request->input('quantity', 1);

        $cartId = $product->id . '_' . $size;

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] += $quantity;
        } else {
            $cart[$cartId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'size' => $size,
                'quantity' => $quantity,
                'image' => !empty($product->images[0]) ? $product->images[0] : 'images/placeholder.jpg',
            ];
        }

        Session::put('cart', $cart);

        if ($request->wantsJson()) {
            $totalQty = 0;
            foreach ($cart as $item) {
                $totalQty += $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cartCount' => $totalQty
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] = (int) $request->input('quantity');
            Session::put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Cart updated!');
        }

        return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
    }

    public function remove($cartId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$cartId])) {
            unset($cart[$cartId]);
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }
}

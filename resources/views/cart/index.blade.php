<x-ramarama-layout>
    <div class="cart-container">
        <h1>Shopping Cart</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(count($cart) > 0)
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                        <tr>
                            <td>
                                <img src="{{ asset($item['image']) }}" width="50"
                                    style="vertical-align: middle; margin-right: 10px;">
                                {{ $item['name'] }}
                            </td>
                            <td>{{ $item['size'] }}</td>
                            <td>RM{{ number_format($item['price'], 2) }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>RM{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="cart-action-btn">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Total: RM{{ number_format($total, 2) }}</h3>
                <div class="actions">
                    <form action="{{ route('cart.clear') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="cart-clear-btn">Clear Cart</button>
                    </form>
                    <a href="{{ route('checkout.index') }}" class="checkout-btn">Checkout</a>
                </div>
            </div>
        @else
            <p>Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="btn">Shop Now</a>
        @endif
    </div>
</x-ramarama-layout>
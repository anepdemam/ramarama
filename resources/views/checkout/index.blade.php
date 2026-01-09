<x-ramarama-layout>
    <div class="cart-container">
        <h1>Checkout</h1>

        <div class="checkout-grid" style="display: flex; gap: 40px; text-align: left;">
            <div class="order-summary" style="flex: 1;">
                <h3>Order Summary</h3>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                            <tr>
                                <td>{{ $item['name'] }} ({{ $item['size'] }})</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>RM{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Grand Total</th>
                            <th>RM{{ number_format($total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="shipping-details" style="flex: 1;">
                <h3>Shipping Information</h3>
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="shipping_name">Full Name:</label>
                        <input type="text" name="shipping_name" id="shipping_name" class="form-control"
                            value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_phone">Phone Number:</label>
                        <input type="text" name="shipping_phone" id="shipping_phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_address">Address:</label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control" rows="4"
                            required></textarea>
                    </div>

                    <button type="submit" class="checkout-btn" style="width: 100%; border: none;">Place Order</button>
                </form>
            </div>
        </div>
    </div>
</x-ramarama-layout>
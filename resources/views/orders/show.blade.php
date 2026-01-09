<x-ramarama-layout>
    <div class="cart-container">
        <h1>Order Details #{{ $order->id }}</h1>

        <div class="order-info"
            style="text-align: left; background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Total:</strong> RM{{ number_format($order->total, 2) }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            <p><strong>Tracking Number:</strong> {{ $order->tracking_number ?? 'Not yet available' }}</p>

            <hr>
            <h3>Shipping Details</h3>
            <p><strong>Name:</strong> {{ $order->shipping_name }}</p>
            <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
            <p><strong>Address:</strong><br>{!! nl2br(e($order->shipping_address)) !!}</p>
        </div>

        <h3>Items</h3>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->size }}</td>
                        <td>RM{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>RM{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <a href="{{ route('orders.index') }}" class="btn">Back to History</a>
        </div>
    </div>
</x-ramarama-layout>
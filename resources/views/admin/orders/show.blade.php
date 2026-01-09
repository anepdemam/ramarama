<x-ramarama-layout>
    <div class="admin-container" style="display: flex;">
        <nav class="unique-admin-sidebar" style="width: 250px; background: #333; color: #fff; padding: 20px;">
            <h3>Admin Panel</h3>
            <ul style="list-style: none; padding: 0;">
                <li><a href="{{ route('admin.dashboard') }}"
                        style="color: #fff; display: block; padding: 10px;">Dashboard</a></li>
                <li><a href="{{ route('admin.products.index') }}"
                        style="color: #fff; display: block; padding: 10px;">Products</a></li>
                <li><a href="{{ route('admin.orders.index') }}"
                        style="color: #666; background: #eee; display: block; padding: 10px;">Orders</a></li>
                <li><a href="{{ url('/') }}" style="color: #fff; display: block; padding: 10px;">View Site</a></li>
            </ul>
        </nav>

        <main class="unique-admin-main" style="flex: 1; padding: 20px;">
            <h1>Order Details #{{ $order->id }}</h1>

            <div class="order-info" style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <p><strong>Customer:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
                <p><strong>Total:</strong> RM{{ number_format($order->total, 2) }}</p>
                <p><strong>Tracking:</strong> {{ $order->tracking_number ?? 'Not updated' }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                <hr>
                <h3>Shipping Address</h3>
                <p>{{ $order->shipping_name }}</p>
                <p>{{ $order->shipping_phone }}</p>
                <p>{!! nl2br(e($order->shipping_address)) !!}</p>
            </div>

            <h3>Order Items</h3>
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
                <a href="{{ route('admin.orders.index') }}" class="btn">Back to Orders</a>
            </div>
        </main>
    </div>
</x-ramarama-layout>
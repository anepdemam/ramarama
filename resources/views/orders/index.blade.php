<x-ramarama-layout>
    <div class="cart-container">
        <h1>Order History</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(count($orders) > 0)
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    @foreach($order->items as $item)
                                        <li>{{ $item->product->name }} ({{ $item->size }}) x {{ $item->quantity }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>RM{{ number_format($order->total, 2) }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-container" style="margin-top: 20px;">
                @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $orders->links() }}
                @endif
            </div>
        @else
            <p>You have no orders yet.</p>
            <a href="{{ route('products.index') }}" class="btn">Shop Now</a>
        @endif
    </div>
</x-ramarama-layout>
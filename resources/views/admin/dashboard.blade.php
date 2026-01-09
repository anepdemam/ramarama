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
                        style="color: #fff; display: block; padding: 10px;">Orders</a></li>
                <li><a href="{{ url('/') }}" style="color: #fff; display: block; padding: 10px;">View Site</a></li>
            </ul>
        </nav>

        <main class="unique-admin-main" style="flex: 1; padding: 20px;">
            <div class="dashboard-header">
                <h1>Dashboard Summary</h1>
            </div>

            <div class="dashboard-summary"
                style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                <div class="summary-box"
                    style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3>{{ $productCount }}</h3>
                    <span>Products</span>
                </div>
                <div class="summary-box"
                    style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3>{{ $customerCount }}</h3>
                    <span>Customers</span>
                </div>
                <div class="summary-box"
                    style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3>RM{{ number_format($totalEarnings, 2) }}</h3>
                    <span>Total Earnings</span>
                </div>
                <div class="summary-box"
                    style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3>{{ $orderCount }}</h3>
                    <span>Total Orders</span>
                </div>
            </div>

            <div class="recent-orders">
                <h2>Recent Orders</h2>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>RM{{ number_format($order->total, 2) }}</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-ramarama-layout>
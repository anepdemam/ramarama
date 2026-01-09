<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <h2 style="font-size: 1.5rem; margin-bottom: 2rem;">Admin Panel</h2>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link active">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-link">
                    <i class="fa-solid fa-box"></i> Products
                </a>
                <a href="{{ route('admin.orders.index') }}" class="admin-nav-link">
                    <i class="fa-solid fa-shopping-cart"></i> Orders
                </a>
                <a href="{{ url('/') }}" class="admin-nav-link">
                    <i class="fa-solid fa-globe"></i> View Site
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main style="flex: 1; padding: 3rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 3rem;">Dashboard</h1>

            <!-- Stats Grid -->
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                <div class="glass" style="padding: 2rem; border-radius: 20px;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--accent)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-box" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.9rem;">Total Products</p>
                            <p style="font-size: 2rem; font-weight: 700;">{{ $productCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="glass" style="padding: 2rem; border-radius: 20px;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 60px; height: 60px; background: linear-gradient(135deg, #22c55e, #10b981); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-users" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.9rem;">Customers</p>
                            <p style="font-size: 2rem; font-weight: 700;">{{ $customerCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="glass" style="padding: 2rem; border-radius: 20px;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 60px; height: 60px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-dollar-sign" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.9rem;">Total Earnings</p>
                            <p style="font-size: 2rem; font-weight: 700;">RM{{ number_format($totalEarnings, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="glass" style="padding: 2rem; border-radius: 20px;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-shopping-bag" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.9rem;">Total Orders</p>
                            <p style="font-size: 2rem; font-weight: 700;">{{ $orderCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="glass" style="padding: 2rem; border-radius: 20px;">
                <h2 style="font-size: 1.5rem; margin-bottom: 2rem;">Recent Orders</h2>

                @if($recentOrders->count() > 0)
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
                                    <td style="color: var(--primary); font-weight: 600;">RM{{ number_format($order->total, 2) }}
                                    </td>
                                    <td>
                                        <span
                                            style="padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; background: {{ $order->status === 'Completed' ? 'rgba(34, 197, 94, 0.1)' : 'rgba(139, 92, 246, 0.1)' }}; color: {{ $order->status === 'Completed' ? '#22c55e' : 'var(--primary)' }};">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="text-align: center; color: var(--text-muted); padding: 2rem;">No orders yet</p>
                @endif
            </div>
        </main>
    </div>
</x-ramarama-layout>
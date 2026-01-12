<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <h2 style="font-size: 1.5rem; margin-bottom: 2rem;">Admin Panel</h2>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-link">
                    <i class="fa-solid fa-box"></i> Products
                </a>
                <a href="{{ route('admin.orders.index') }}" class="admin-nav-link active">
                    <i class="fa-solid fa-shopping-cart"></i> Orders
                </a>
                <a href="{{ url('/') }}" class="admin-nav-link">
                    <i class="fa-solid fa-globe"></i> View Site
                </a>
            </nav>
        </aside>

        <main style="flex: 1; padding: 3rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 3rem;">Manage Orders</h1>

            @if(session('success'))
                <div
                    style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; color: #22c55e;">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="glass" style="padding: 2rem; border-radius: 20px;">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tracking</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td style="color: var(--primary); font-weight: 600;">RM{{ number_format($order->total, 2) }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            style="padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; background: {{ $order->status === 'Completed' ? 'rgba(34, 197, 94, 0.1)' : 'rgba(139, 92, 246, 0.1)' }}; color: {{ $order->status === 'Completed' ? '#22c55e' : 'var(--text-main)' }}; border: 1px solid var(--glass-border); width: auto;">
                                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>
                                                Processing</option>
                                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>
                                                Completed</option>
                                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>
                                                Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST"
                                        style="display: flex; gap: 0.5rem;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $order->status }}">
                                        <input type="text" name="tracking_number" value="{{ $order->tracking_number }}"
                                            placeholder="Tracking #"
                                            style="padding: 0.4rem 0.8rem; height: auto; font-size: 0.9rem; width: 150px;">
                                        <button type="submit" class="btn-primary"
                                            style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                            <i class="fa-solid fa-save"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem; transition: var(--transition);"
                                        onmouseover="this.style.color='var(--primary)'"
                                        onmouseout="this.style.color='var(--text-muted)'">
                                        View Details <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-ramarama-layout>
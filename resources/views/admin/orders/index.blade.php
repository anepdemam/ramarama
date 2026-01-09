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
            <h1>Manage Orders</h1>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="manage-products-table">
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
                            <td>RM{{ number_format($order->total, 2) }}</td>
                            <td>
                                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending
                                        </option>
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
                                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $order->status }}">
                                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}"
                                        placeholder="Add tracking">
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </div>
</x-ramarama-layout>
<x-ramarama-layout>
    <div class="admin-container" style="display: flex;">
        <nav class="unique-admin-sidebar" style="width: 250px; background: #333; color: #fff; padding: 20px;">
            <h3>Admin Panel</h3>
            <ul style="list-style: none; padding: 0;">
                <li><a href="{{ route('admin.dashboard') }}"
                        style="color: #fff; display: block; padding: 10px;">Dashboard</a></li>
                <li><a href="{{ route('admin.products.index') }}"
                        style="color: #666; background: #eee; display: block; padding: 10px;">Products</a></li>
                <li><a href="{{ route('admin.orders.index') }}"
                        style="color: #fff; display: block; padding: 10px;">Orders</a></li>
                <li><a href="{{ url('/') }}" style="color: #fff; display: block; padding: 10px;">View Site</a></li>
            </ul>
        </nav>

        <main class="unique-admin-main" style="flex: 1; padding: 20px;">
            <div class="header"
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>Manage Products</h1>
                <a href="{{ route('admin.products.create') }}" class="btn">Add New Product</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="manage-products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category }}</td>
                            <td>RM{{ number_format($product->price, 2) }}</td>
                            <td>
                                S: {{ $product->stock_small }} |
                                M: {{ $product->stock_medium }} |
                                L: {{ $product->stock_large }}
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" style="color: blue;">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="color: red; border: none; background: none; cursor: pointer;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </div>
</x-ramarama-layout>
<x-ramarama-layout>
    <div style="display: flex; min-height: 100vh; padding-top: 80px;">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <h2 style="font-size: 1.5rem; margin-bottom: 2rem;">Admin Panel</h2>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-link active">
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

        <main style="flex: 1; padding: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
                <h1 style="font-size: 2.5rem;">Manage Products</h1>
                <a href="{{ route('admin.products.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Add New Product
                </a>
            </div>

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
                            <th>Image</th>
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
                                <td>
                                    <img src="{{ asset($product->images[0] ?? 'images/placeholder.jpg') }}"
                                        alt="{{ $product->name }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category }}</td>
                                <td style="color: var(--primary); font-weight: 600;">
                                    RM{{ number_format($product->price, 2) }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; font-size: 0.8rem;">
                                        <span
                                            style="background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;">S:
                                            {{ $product->stock_small }}</span>
                                        <span
                                            style="background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;">M:
                                            {{ $product->stock_medium }}</span>
                                        <span
                                            style="background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;">L:
                                            {{ $product->stock_large }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            style="color: var(--primary); text-decoration: none; padding: 0.4rem; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: rgba(139, 92, 246, 0.1);">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')"
                                                style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; cursor: pointer; padding: 0.4rem; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-ramarama-layout>
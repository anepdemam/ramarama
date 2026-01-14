@props(['active' => 'dashboard'])

<aside class="admin-sidebar">
    <h2 style="font-size: 1.5rem; margin-bottom: 2rem;">Admin Panel</h2>
    <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ $active === 'dashboard' ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('admin.products.index') }}"
            class="admin-nav-link {{ $active === 'products' ? 'active' : '' }}">
            <i class="fa-solid fa-box"></i> Products
        </a>
        <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ $active === 'orders' ? 'active' : '' }}">
            <i class="fa-solid fa-shopping-cart"></i> Orders
        </a>
        <a href="{{ route('admin.vouchers.index') }}"
            class="admin-nav-link {{ $active === 'vouchers' ? 'active' : '' }}">
            <i class="fa-solid fa-tag"></i> Vouchers
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link {{ $active === 'reviews' ? 'active' : '' }}">
            <i class="fa-solid fa-star"></i> Reviews
        </a>
        <a href="{{ url('/') }}" class="admin-nav-link">
            <i class="fa-solid fa-globe"></i> View Site
        </a>
    </nav>
</aside>
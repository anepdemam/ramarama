<x-ramarama-layout>
    <section style="padding-top: 8rem;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 3rem; margin-bottom: 1rem;">Collections</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Discover your next statement piece</p>
        </div>

        <!-- Search & Filters -->
        <div style="max-width: 1200px; margin: 0 auto 3rem; padding: 0 2rem;">
            <form method="GET" action="{{ route('products.index') }}"
                style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; align-items: center;">
                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                    style="max-width: 400px; flex: 1;">

                <!-- Custom Category Dropdown -->
                <div class="custom-select-wrapper" style="max-width: 200px; position: relative;">
                    <select name="category" class="custom-select">
                        <option value="">All Categories</option>
                        <option value="Tops" {{ request('category') == 'Tops' ? 'selected' : '' }}>Tops</option>
                        <option value="Hoodies" {{ request('category') == 'Hoodies' ? 'selected' : '' }}>Hoodies</option>
                        <option value="Bottoms" {{ request('category') == 'Bottoms' ? 'selected' : '' }}>Bottoms</option>
                        <option value="Outerwear" {{ request('category') == 'Outerwear' ? 'selected' : '' }}>Outerwear
                        </option>
                    </select>
                    <i class="fa-solid fa-chevron-down"
                        style="position: absolute; right: 1.2rem; top: 50%; transform: translateY(-50%); color: var(--primary); pointer-events: none; font-size: 0.8rem;"></i>
                </div>

                <!-- Custom Sort Dropdown -->
                <div class="custom-select-wrapper" style="max-width: 200px; position: relative;">
                    <select name="sort" class="custom-select">
                        <option value="">Sort By</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to
                            High
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to
                            Low
                        </option>
                    </select>
                    <i class="fa-solid fa-chevron-down"
                        style="position: absolute; right: 1.2rem; top: 50%; transform: translateY(-50%); color: var(--primary); pointer-events: none; font-size: 0.8rem;"></i>
                </div>

                <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem;">
                    <i class="fa-solid fa-search"></i> Search
                </button>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" style="padding: 0 2rem;">
            @forelse($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                        <div class="product-image-wrap">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}">
                            @else
                                <div
                                    style="width: 100%; height: 100%; background: var(--surface); display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-image" style="font-size: 3rem; color: var(--text-muted);"></i>
                                </div>
                            @endif
                        </div>
                        <div class="product-info">
                            <span
                                style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">{{ $product->category }}</span>
                            <h3>{{ $product->name }}</h3>
                            <span class="price">RM{{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('products.show', $product) }}" class="btn-primary"
                                style="width: 100%; text-align: center; font-size: 0.9rem; padding: 0.7rem;">
                                View Details
                            </a>
                        </div>
                    </a>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 0;">
                    <i class="fa-solid fa-box-open"
                        style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-muted); font-size: 1.2rem;">No products found</p>
                </div>
            @endforelse
        </div>
    </section>
</x-ramarama-layout>
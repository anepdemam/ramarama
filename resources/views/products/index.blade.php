<x-ramarama-layout>
    <div class="product-index-header">
        <h1 class="animate-up">Collections</h1>
        <p class="animate-up" style="animation-delay: 0.1s;">Discover pieces that redefine the boundaries of expression
        </p>
    </div>

    <!-- Search & Filters -->
    <section class="search-filter-section">
        <form method="GET" action="{{ route('products.index') }}" class="search-form" id="filterForm">
            <div class="search-input-group animate-up" style="animation-delay: 0.2s;">
                <input type="text" name="search" id="searchInput" placeholder="Search products..."
                    value="{{ request('search') }}" class="main-search-input" autocomplete="off">
                <i class="fa-solid fa-search"
                    style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); color: var(--primary);"></i>

                <!-- Predictive Results -->
                <div class="predictive-results" id="searchResults">
                    <!-- Results dynamically loaded -->
                </div>
            </div>

            <!-- Custom Category Dropdown -->
            <div class="dropdown-container animate-up" style="animation-delay: 0.3s;">
                <input type="hidden" name="category" id="categoryInput" value="{{ request('category') }}">
                <div class="dropdown-trigger" id="categoryTrigger">
                    <span class="trigger-text">{{ request('category') ?: 'All Categories' }}</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="dropdown-menu" id="categoryMenu">
                    <div class="dropdown-item" data-value="">All Categories</div>
                    @foreach(['Tops', 'Hoodies', 'Bottoms', 'Outerwear'] as $cat)
                        <div class="dropdown-item {{ request('category') == $cat ? 'selected' : '' }}"
                            data-value="{{ $cat }}">{{ $cat }}</div>
                    @endforeach
                </div>
            </div>

            <!-- Custom Sort Dropdown -->
            <div class="dropdown-container animate-up" style="animation-delay: 0.4s;">
                <input type="hidden" name="sort" id="sortInput" value="{{ request('sort') }}">
                <div class="dropdown-trigger" id="sortTrigger">
                    @php
                        $sortLabel = 'Sort By';
                        if (request('sort') == 'price_asc')
                            $sortLabel = 'Price: Low to High';
                        if (request('sort') == 'price_desc')
                            $sortLabel = 'Price: High to Low';
                    @endphp
                    <span class="trigger-text">{{ $sortLabel }}</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="dropdown-menu" id="sortMenu">
                    <div class="dropdown-item" data-value="">Default</div>
                    <div class="dropdown-item {{ request('sort') == 'price_asc' ? 'selected' : '' }}"
                        data-value="price_asc">Price: Low to High</div>
                    <div class="dropdown-item {{ request('sort') == 'price_desc' ? 'selected' : '' }}"
                        data-value="price_desc">Price: High to Low</div>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="padding: 0.8rem 2.5rem; height: 60px;">
                Filter
            </button>
        </form>
    </section>

    <!-- Products Grid -->
    <div class="products-grid" style="max-width: 1400px; margin: 0 auto; padding: 0 2rem 10rem;">
        @forelse($products as $product)
            <div class="product-card animate-up">
                <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                    <div class="product-image-wrap">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}">
                        @else
                            <div class="placeholder-image">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                        <div class="card-overlay">
                            <span class="view-tag">View Item</span>
                        </div>
                    </div>
                    <div class="product-info">
                        <span class="category">{{ $product->category }}</span>
                        <h3>{{ $product->name }}</h3>
                        <div class="price-row">
                            <span class="price">RM{{ number_format($product->price, 2) }}</span>
                            @auth
                                <div class="add-btn"><i class="fa-solid fa-plus"></i></div>
                            @else
                                <div class="add-btn" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i
                                        class="fa-solid fa-lock" style="font-size: 0.7rem;"></i></div>
                            @endauth
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 10rem 0;" class="animate-up">
                <i class="fa-solid fa-box-open"
                    style="font-size: 5rem; color: rgba(139, 92, 246, 0.2); margin-bottom: 2rem; display: block;"></i>
                <p style="color: var(--text-muted); font-size: 1.5rem;">No products found</p>
                <a href="{{ route('products.index') }}" class="btn-link" style="margin-top: 2rem;">Clear all filters</a>
            </div>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Custom Dropdowns Logic ---
            const setupDropdown = (triggerId, menuId, inputId) => {
                const trigger = document.getElementById(triggerId);
                const menu = document.getElementById(menuId);
                const input = document.getElementById(inputId);
                const items = menu.querySelectorAll('.dropdown-item');

                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Close other open menus
                    document.querySelectorAll('.dropdown-menu.active').forEach(m => {
                        if (m !== menu) m.classList.remove('active');
                    });
                    document.querySelectorAll('.dropdown-trigger.active').forEach(t => {
                        if (t !== trigger) t.classList.remove('active');
                    });

                    menu.classList.toggle('active');
                    trigger.classList.toggle('active');
                });

                items.forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.dataset.value;
                        const text = item.textContent;
                        input.value = val;
                        trigger.querySelector('.trigger-text').textContent = text;
                        menu.classList.remove('active');
                        trigger.classList.remove('active');

                        // Submit form on selection
                        document.getElementById('filterForm').submit();
                    });
                });
            };

            setupDropdown('categoryTrigger', 'categoryMenu', 'categoryInput');
            setupDropdown('sortTrigger', 'sortMenu', 'sortInput');

            // Close dropdowns on outside click
            window.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-menu.active').forEach(m => m.classList.remove('active'));
                document.querySelectorAll('.dropdown-trigger.active').forEach(t => t.classList.remove('active'));
            });

            // --- Predictive Search Logic ---
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            let debounceTimer;

            searchInput.addEventListener('input', function () {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    searchResults.classList.remove('active');
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('products.suggestions') }}?query=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length > 0) {
                                let html = '';
                                data.forEach(item => {
                                    html += `
                                        <a href="${item.url}" class="result-item">
                                            <img src="${item.image || '{{ asset('images/global/placeholder.png') }}'}" class="result-img">
                                            <div class="result-info">
                                                <h4>${item.name}</h4>
                                                <span class="result-price">RM ${item.price}</span>
                                            </div>
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </a>
                                    `;
                                });
                                searchResults.innerHTML = html;
                                searchResults.classList.add('active');
                            } else {
                                searchResults.innerHTML = '<div class="no-results">No products found</div>';
                                searchResults.classList.add('active');
                            }
                        });
                }, 300);
            });

            // Close search results on outside click
            window.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.remove('active');
                }
            });
        });
    </script>
</x-ramarama-layout>
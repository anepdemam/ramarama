<x-ramarama-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <form method="GET" action="{{ route('products.index') }}" class="search-form">
        <h1>Our Products</h1>

        <div class="search-section">
            <input type="text" id="search" name="search" placeholder="Search products..."
                value="{{ request('search') }}" autocomplete="off">
            <button type="submit">Search</button>

            <button type="button" id="filter-icon" class="filter-icon">
                <i class="fas fa-filter"></i>
            </button>

            <div id="filter-dropdown" class="filter-dropdown" style="display: none;">
                <select name="category">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->category }}" {{ request('category') == $cat->category ? 'selected' : '' }}>
                            {{ $cat->category }}
                        </option>
                    @endforeach
                </select>

                <select name="sort">
                    <option value="">Sort By</option>
                    <option value="low_to_high" {{ request('sort') == 'low_to_high' ? 'selected' : '' }}>Price: Low to
                        High</option>
                    <option value="high_to_low" {{ request('sort') == 'high_to_low' ? 'selected' : '' }}>Price: High to
                        Low</option>
                </select>

                <button type="submit">Apply Filters</button>
            </div>
        </div>
    </form>

    <ul id="autocomplete-results" style="display: none;"></ul>

    <section class="products" id="products">
        <div class="product-grid">
            @forelse ($products as $product)
                <div class="product">
                    @php
                        $images = $product->images;
                        $firstImage = !empty($images[0]) ? asset($images[0]) : asset('images/placeholder.jpg');
                        $secondImage = !empty($images[1]) ? asset($images[1]) : $firstImage;
                    @endphp

                    <div class="product-image-container">
                        <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="product-image">
                        <img src="{{ $secondImage }}" alt="{{ $product->name }}" class="hover-image">
                    </div>

                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        <p class="product-price">RM{{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product) }}" class="btn">View Details</a>
                    </div>
                </div>
            @empty
                <h3>No products found matching your search or filters!</h3>
            @endforelse
        </div>
    </section>

    <script>
        const searchInput = document.getElementById('search');
        const resultsContainer = document.getElementById('autocomplete-results');
        const filterIcon = document.getElementById('filter-icon');
        const filterDropdown = document.getElementById('filter-dropdown');

        searchInput.addEventListener('keyup', function () {
            const query = searchInput.value.trim();
            if (query.length > 0) {
                // In Laravel, we can implement a dedicated route for this
                fetch('{{ route('products.suggestions') }}?query=' + query)
                    .then(response => response.json())
                    .then(data => {
                        resultsContainer.style.display = 'block';
                        resultsContainer.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const listItem = document.createElement('li');
                                listItem.textContent = item;
                                resultsContainer.appendChild(listItem);
                            });
                        } else {
                            resultsContainer.innerHTML = '<li>No results found</li>';
                        }
                    })
                    .catch(error => console.error('Error fetching suggestions:', error));
            } else {
                resultsContainer.style.display = 'none';
            }
        });

        filterIcon.addEventListener('click', function () {
            filterDropdown.style.display = (filterDropdown.style.display === 'block') ? 'none' : 'block';
        });

        document.addEventListener('click', function (event) {
            if (!filterDropdown.contains(event.target) && event.target !== filterIcon) {
                filterDropdown.style.display = 'none';
            }
            if (!resultsContainer.contains(event.target) && event.target !== searchInput) {
                resultsContainer.style.display = 'none';
            }
        });
    </script>
</x-ramarama-layout>
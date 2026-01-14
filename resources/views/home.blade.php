<x-ramarama-layout>
    <section class="hero">
        <div class="hero-bg-glow"></div>
        <div class="hero-container">
            <div class="hero-content animate-up">
                <h1>A World Without<br>Insecurities</h1>
                <p>Premium streetwear designed to empower your confidence. Express yourself without limits.</p>
                <a href="{{ route('products.index') }}" class="btn-primary">Explore Collections</a>
            </div>

            <div class="hero-image animate-up" style="animation-delay: 0.2s;">
                <img src="{{ asset('images/hero/bf2.png') }}" alt="Ramarama Hero">
            </div>
        </div>
    </section>

    <section style="padding: 6rem 2rem;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Featured Drops</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Curated pieces that define the culture</p>
        </div>

        <div class="carousel-container">
            <button class="carousel-btn prev" id="prevBtn" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="products-grid products-scroll" id="productGrid">
                @php
                    // Triple items for seamless infinite scroll
                    $loopProducts = collect($products)->concat($products)->concat($products);
                @endphp
                @foreach($loopProducts as $product)
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
                                <h3>{{ $product->name }}</h3>
                                <span class="price">RM{{ number_format($product->price, 2) }}</span>
                                <div class="btn-primary"
                                    style="width: 100%; text-align: center; font-size: 0.9rem; padding: 0.7rem;">
                                    View Details
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <button class="carousel-btn next" id="nextBtn" aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        @if(count($products) === 0)
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fa-solid fa-box-open" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <p style="color: var(--text-muted); font-size: 1.2rem;">New drops coming soon...</p>
            </div>
        @endif
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grid = document.getElementById('productGrid');
            const next = document.getElementById('nextBtn');
            const prev = document.getElementById('prevBtn');

            if (!grid || !next || !prev) return;

            // Start in the middle set of items
            const originalWidth = grid.scrollWidth / 3;
            grid.scrollLeft = originalWidth;

            // Scroll by one full visible page
            const getScrollAmount = () => grid.clientWidth + 32;

            const handleInfiniteScroll = () => {
                if (grid.scrollLeft <= 5) {
                    grid.style.scrollBehavior = 'auto';
                    grid.scrollLeft = originalWidth;
                    grid.style.scrollBehavior = 'smooth';
                } else if (grid.scrollLeft >= (grid.scrollWidth - grid.clientWidth - 5)) {
                    grid.style.scrollBehavior = 'auto';
                    grid.scrollLeft = originalWidth - grid.clientWidth;
                    grid.style.scrollBehavior = 'smooth';
                }
            };

            // debounce/throttle could be added but let's keep it simple for now
            grid.addEventListener('scroll', handleInfiniteScroll);

            next.addEventListener('click', () => {
                grid.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            });

            prev.addEventListener('click', () => {
                grid.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
            });
        });
    </script>
</x-ramarama-layout>
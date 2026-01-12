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
                <div class="placeholder-box">
                    <i class="fa-solid fa-image"></i>
                    <span>Hero Image Placeholder</span>
                </div>
            </div>
        </div>
    </section>

    <section style="padding: 6rem 2rem;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Featured Drops</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Curated pieces that define the culture</p>
        </div>

        <div class="products-grid">
            @foreach($products as $product)
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
                            <a href="{{ route('products.show', $product) }}" class="btn-primary"
                                style="width: 100%; text-align: center; font-size: 0.9rem; padding: 0.7rem;">
                                View Details
                            </a>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        @if($products->isEmpty())
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fa-solid fa-box-open" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <p style="color: var(--text-muted); font-size: 1.2rem;">New drops coming soon...</p>
            </div>
        @endif
    </section>
</x-ramarama-layout>
<x-ramarama-layout>
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Welcome to Our Store</h1>
            <p>Discover the latest products and enjoy great deals!</p>
            <a href="{{ route('products.index') }}" class="btn">Shop Now</a>
        </div>
    </section>

    <section class="featured-products">
        <div class="container">
            <h2>Featured Products</h2>
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product">
                        @php
                            $images = $product->images;
                            $firstImage = !empty($images[0]) ? asset($images[0]) : asset('images/placeholder.jpg');
                        @endphp
                        <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="product-image">
                        <h3>{{ $product->name }}</h3>
                        <p>RM{{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product) }}" class="btn">Details</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-ramarama-layout>
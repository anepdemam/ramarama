<x-ramarama-layout>
    <div class="product-details-container">
        @php
            $images = $product->images;
            $firstImage = !empty($images[0]) ? asset($images[0]) : asset('images/placeholder.jpg');
        @endphp

        <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="product-details-img">

        <div class="product-info">
            <h1>{{ $product->name }}</h1>
            <p class="category">Category: {{ $product->category }}</p>
            <p class="price">RM{{ number_format($product->price, 2) }}</p>
            <div class="description">
                {!! nl2br(e($product->description)) !!}
            </div>

            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="size">Size:</label>
                    <select name="size" id="size" class="form-control" required>
                        <option value="S">Small ({{ $product->stock_small }})</option>
                        <option value="M">Medium ({{ $product->stock_medium }})</option>
                        <option value="L">Large ({{ $product->stock_large }})</option>
                        <option value="XL">Extra Large ({{ $product->stock_xl }})</option>
                        <option value="2XL">2XL ({{ $product->stock_2xl }})</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control" required>
                </div>
                <button type="submit" class="btn">Add to Cart</button>
            </form>

            <a href="{{ route('products.index') }}" class="back-btn">Back to Products</a>
        </div>
    </div>
</x-ramarama-layout>
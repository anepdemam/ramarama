<x-ramarama-layout>
    <section style="padding-top: 8rem;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <a href="{{ route('products.index') }}"
                style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Collections
            </a>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-top: 2rem;">
                <!-- Product Images -->
                <div>
                    @if($product->images && count($product->images) > 0)
                        <div style="aspect-ratio: 1/1; border-radius: 20px; overflow: hidden; margin-bottom: 1rem;">
                            <img id="mainImage" src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        @if(count($product->images) > 1)
                            <div style="display: flex; gap: 1rem;">
                                @foreach($product->images as $index => $image)
                                    <img src="{{ asset($image) }}" alt="{{ $product->name }}"
                                        onclick="document.getElementById('mainImage').src='{{ asset($image) }}'"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; cursor: pointer; border: 2px solid {{ $index === 0 ? 'var(--primary)' : 'transparent' }}; transition: var(--transition);"
                                        onmouseover="this.style.borderColor='var(--primary)'"
                                        onmouseout="this.style.borderColor='{{ $index === 0 ? 'var(--primary)' : 'transparent' }}'">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div
                            style="aspect-ratio: 1/1; background: var(--surface); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-image" style="font-size: 5rem; color: var(--text-muted);"></i>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div>
                    <span
                        style="font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">{{ $product->category }}</span>
                    <h1 style="font-size: 2.5rem; margin: 1rem 0;">{{ $product->name }}</h1>
                    <p style="font-size: 2rem; color: var(--primary); font-weight: 700; margin-bottom: 2rem;">
                        RM{{ number_format($product->price, 2) }}</p>

                    <p style="color: var(--text-muted); line-height: 1.8; margin-bottom: 2rem;">
                        {{ $product->description }}</p>

                    <!-- Size Selection -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div style="margin-bottom: 2rem;">
                            <label style="display: block; margin-bottom: 1rem; font-weight: 600;">Select Size</label>
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                @foreach(['Small' => $product->stock_small, 'Medium' => $product->stock_medium, 'Large' => $product->stock_large, 'XL' => $product->stock_xl, '2XL' => $product->stock_2xl] as $size => $stock)
                                    @if($stock > 0)
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="size" value="{{ $size }}" required style="display: none;"
                                                class="size-radio">
                                            <div class="size-option"
                                                style="padding: 1rem 1.5rem; border: 2px solid var(--glass-border); border-radius: 12px; transition: var(--transition); text-align: center; min-width: 80px;">
                                                <div style="font-weight: 600;">{{ $size }}</div>
                                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $stock }} left
                                                </div>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <label style="display: block; margin-bottom: 1rem; font-weight: 600;">Quantity</label>
                            <input type="number" name="quantity" value="1" min="1" max="10" style="max-width: 150px;">
                        </div>

                        <button type="submit" class="btn-primary"
                            style="width: 100%; padding: 1.2rem; font-size: 1.1rem;">
                            <i class="fa-solid fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>
        .size-radio:checked+.size-option {
            border-color: var(--primary);
            background: rgba(139, 92, 246, 0.1);
        }

        .size-option:hover {
            border-color: var(--primary);
        }
    </style>
</x-ramarama-layout>
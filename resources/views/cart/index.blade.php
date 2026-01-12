<x-ramarama-layout>
    <section style="padding-top: 8rem; min-height: 70vh;">
        <div style="max-width: 1000px; margin: 0 auto; padding: 0 2rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 3rem; text-align: center;">Shopping Cart</h1>

            @if(session('success'))
                <div
                    style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; color: #22c55e;">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(count($cart) > 0)
                <div class="glass" style="border-radius: 20px; padding: 2rem; margin-bottom: 2rem;">
                    @foreach($cart as $id => $item)
                        <div
                            style="display: grid; grid-template-columns: 100px 1fr auto; gap: 2rem; padding: 1.5rem 0; border-bottom: 1px solid var(--glass-border);">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}"
                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px;">

                            <div>
                                <h3 style="margin-bottom: 0.5rem;">{{ $item['name'] }}</h3>
                                <p style="color: var(--text-muted); font-size: 0.9rem;">Size: {{ $item['size'] }}</p>
                                <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="color: var(--text-muted); font-size: 0.9rem;">Quantity:</span>
                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                            onchange="this.form.submit()"
                                            style="width: 60px; padding: 0.2rem 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 6px; color: white;">
                                    </form>
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem;">
                                    RM{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </p>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="background: transparent; border: 1px solid #ef4444; color: #ef4444; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; transition: var(--transition);"
                                        onmouseover="this.style.background='#ef4444'; this.style.color='white'"
                                        onmouseout="this.style.background='transparent'; this.style.color='#ef4444'">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <div>
                        <p style="font-size: 1.2rem; color: var(--text-muted);">Total</p>
                        <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
                            RM{{ number_format($total, 2) }}</p>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background: transparent; border: 1px solid var(--glass-border); color: var(--text-muted); padding: 1rem 2rem; border-radius: 50px; cursor: pointer; transition: var(--transition);"
                                onmouseover="this.style.borderColor='#ef4444'; this.style.color='#ef4444'"
                                onmouseout="this.style.borderColor='var(--glass-border)'; this.style.color='var(--text-muted)'">
                                Clear Cart
                            </button>
                        </form>
                        <a href="{{ route('checkout.index') }}" class="btn-primary" style="padding: 1rem 3rem;">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @else
                <div style="text-align: center; padding: 4rem 0;">
                    <i class="fa-solid fa-cart-shopping"
                        style="font-size: 5rem; color: var(--text-muted); margin-bottom: 1.5rem;"></i>
                    <h2 style="margin-bottom: 1rem;">Your cart is empty</h2>
                    <p style="color: var(--text-muted); margin-bottom: 2rem;">Start adding some items to your cart</p>
                    <a href="{{ route('products.index') }}" class="btn-primary">
                        <i class="fa-solid fa-shopping-bag"></i> Shop Now
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-ramarama-layout>
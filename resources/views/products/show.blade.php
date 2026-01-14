<x-ramarama-layout>
    <section style="padding-top: 8rem;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <a href="{{ route('products.index') }}"
                style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; transition: var(--transition);"
                onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
                <i class="fa-solid fa-arrow-left"></i> Back to Collections
            </a>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-top: 2rem;">
                <!-- Product Images -->
                <div>
                    @if($product->images && count($product->images) > 0)
                        <div class="glass"
                            style="aspect-ratio: 1/1; border-radius: 20px; overflow: hidden; margin-bottom: 1rem; position: relative;">
                            <img id="mainImage" src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}"
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">

                            <!-- Image zoom indicator -->
                            <div
                                style="position: absolute; bottom: 1rem; right: 1rem; background: rgba(0,0,0,0.5); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem;">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Hover to zoom
                            </div>
                        </div>
                        @if(count($product->images) > 1)
                            <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
                                @foreach($product->images as $index => $image)
                                    <img src="{{ asset($image) }}" alt="{{ $product->name }}"
                                        onclick="document.getElementById('mainImage').src='{{ asset($image) }}'; document.querySelectorAll('.thumbnail-img').forEach(img => img.style.borderColor='transparent'); this.style.borderColor='var(--primary)';"
                                        class="thumbnail-img"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; cursor: pointer; border: 2px solid {{ $index === 0 ? 'var(--primary)' : 'transparent' }}; transition: var(--transition); flex-shrink: 0;"
                                        onmouseover="this.style.transform='scale(1.05)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="glass"
                            style="aspect-ratio: 1/1; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-image" style="font-size: 5rem; color: var(--text-muted);"></i>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div>
                    <span
                        style="font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">{{ $product->category }}</span>
                    <h1 style="font-size: 2.5rem; margin: 1rem 0;">{{ $product->name }}</h1>

                    <!-- Price with badge -->
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                        <p style="font-size: 2rem; color: var(--primary); font-weight: 700; margin: 0;">
                            RM{{ number_format($product->price, 2) }}
                        </p>
                        @if(array_sum([$product->stock_small, $product->stock_medium, $product->stock_large, $product->stock_xl, $product->stock_2xl]) < 10)
                            <span
                                style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                <i class="fa-solid fa-fire"></i> Low Stock
                            </span>
                        @endif
                    </div>

                    <p style="color: var(--text-muted); line-height: 1.8; margin-bottom: 2rem;">
                        {{ $product->description }}
                    </p>

                    <!-- Product Features -->
                    <div class="glass" style="padding: 1.5rem; border-radius: 16px; margin-bottom: 2rem;">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-truck-fast" style="color: var(--primary);"></i>
                                <span style="font-size: 0.9rem;">Free Shipping</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i>
                                <span style="font-size: 0.9rem;">Secure Payment</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-rotate-left" style="color: var(--primary);"></i>
                                <span style="font-size: 0.9rem;">Easy Returns</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-certificate" style="color: var(--primary);"></i>
                                <span style="font-size: 0.9rem;">Authentic</span>
                            </div>
                        </div>
                    </div>

                    <!-- Size Selection -->
                    <form action="{{ route('cart.add', $product) }}" method="POST">
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
                                            <div class="size-option glass"
                                                style="padding: 1rem 1.5rem; border: 2px solid var(--glass-border); border-radius: 12px; transition: var(--transition); text-align: center; min-width: 80px;">
                                                <div style="font-weight: 600;">{{ $size }}</div>
                                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $stock }} left
                                                </div>
                                            </div>
                                        </label>
                                    @else
                                        <div class="glass"
                                            style="padding: 1rem 1.5rem; border: 2px solid var(--glass-border); border-radius: 12px; text-align: center; min-width: 80px; opacity: 0.5; position: relative;">
                                            <div style="font-weight: 600; text-decoration: line-through;">{{ $size }}</div>
                                            <div style="font-size: 0.8rem; color: #ef4444;">Out of Stock</div>
                                        </div>
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

    <!-- Reviews Section -->
    <section style="padding: 4rem 0; background: rgba(255, 255, 255, 0.02);">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
                <div>
                    <h2 style="font-size: 2.2rem; margin-bottom: 0.5rem;">Customer Reviews</h2>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="color: #fbbf24; font-size: 1.2rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= round($averageRating ?? 0) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <span style="color: var(--text-muted);">{{ number_format($averageRating ?? 0, 1) }} out of
                            5</span>
                        <span style="color: var(--text-muted); opacity: 0.5;">|</span>
                        <span style="color: var(--text-muted);">{{ count($reviews) }} reviews</span>
                    </div>
                </div>
                @auth
                    <a href="#reviewForm" class="btn-secondary" style="padding: 0.8rem 1.5rem;">Write a Review</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary" style="padding: 0.8rem 1.5rem;">Login to Review</a>
                @endauth
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Review List -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @forelse($reviews as $review)
                        <div class="glass" style="padding: 2rem; border-radius: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                <div style="display: flex; items-center; gap: 1rem;">
                                    <div
                                        style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $review->user->name }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                                            {{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div style="color: #fbbf24;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            <p style="color: var(--text-muted); line-height: 1.6;">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div class="glass"
                            style="padding: 3rem; border-radius: 20px; text-align: center; color: var(--text-muted);">
                            <i class="fa-regular fa-comment-dots"
                                style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                            <p>No reviews yet. Be the first to share your experience!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Review Form -->
                @auth
                    <div id="reviewForm" class="glass"
                        style="padding: 2.5rem; border-radius: 24px; height: fit-content; position: sticky; top: 100px;">
                        <h3 style="font-size: 1.5rem; margin-bottom: 2rem;">Share Your Feedback</h3>

                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 2rem;">
                                <label style="display: block; margin-bottom: 1rem; color: var(--text-muted);">Your
                                    Rating</label>
                                <div class="rating-input"
                                    style="display: flex; gap: 0.5rem; font-size: 1.5rem; color: #fbbf24; cursor: pointer;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-regular fa-star star-btn" data-rating="{{ $i }}"></i>
                                    @endfor
                                    <input type="hidden" name="rating" id="ratingValue" required>
                                </div>
                            </div>

                            <div style="margin-bottom: 2rem;">
                                <label for="comment"
                                    style="display: block; margin-bottom: 1rem; color: var(--text-muted);">Your
                                    Experience</label>
                                <textarea name="comment" id="comment" rows="5"
                                    placeholder="What did you think of the material, fit, and design?" required></textarea>
                            </div>

                            <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem;">
                                Submit Review
                            </button>
                            <p style="font-size: 0.8rem; color: var(--text-muted); text-align: center; margin-top: 1rem;">
                                <i class="fa-solid fa-circle-info"></i> Your review will be visible once approved by our
                                team.
                            </p>
                        </form>
                    </div>

                    <script>
                        document.querySelectorAll('.star-btn').forEach(star => {
                            star.addEventListener('click', function () {
                                const rating = this.dataset.rating;
                                document.getElementById('ratingValue').value = rating;

                                document.querySelectorAll('.star-btn').forEach(s => {
                                    if (s.dataset.rating <= rating) {
                                        s.classList.replace('fa-regular', 'fa-solid');
                                    } else {
                                        s.classList.replace('fa-solid', 'fa-regular');
                                    }
                                });
                            });

                            star.addEventListener('mouseover', function () {
                                const rating = this.dataset.rating;
                                document.querySelectorAll('.star-btn').forEach(s => {
                                    if (s.dataset.rating <= rating) {
                                        s.style.transform = 'scale(1.2)';
                                    }
                                });
                            });

                            star.addEventListener('mouseout', function () {
                                document.querySelectorAll('.star-btn').forEach(s => s.style.transform = 'scale(1)');
                            });
                        });
                    </script>
                @endauth
            </div>
        </div>
    </section>

    <style>
        .size-radio:checked+.size-option {
            border-color: var(--primary);
            background: rgba(139, 92, 246, 0.1);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .size-option:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        #mainImage:hover {
            transform: scale(1.05);
        }

        .star-btn {
            transition: var(--transition);
        }
    </style>
</x-ramarama-layout>

<!-- Success Modal -->
<div id="successModal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000; align-items: center; justify-content: center; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px);">
    <div class="glass animate-up"
        style="padding: 2.5rem; border-radius: 20px; max-width: 450px; width: 90%; text-align: center; border: 1px solid var(--primary-glow);">
        <div
            style="width: 60px; height: 60px; background: rgba(34, 197, 94, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <i class="fa-solid fa-check" style="font-size: 1.5rem; color: #4ade80;"></i>
        </div>

        <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Added to Cart!</h3>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Item has been successfully added to your cart.</p>

        <div style="display: flex; gap: 1rem; flex-direction: column;">
            <a href="{{ route('cart.index') }}" class="btn-primary" style="text-align: center;">View Cart</a>
            <button onclick="closeModal()"
                style="background: transparent; border: 1px solid var(--glass-border); color: var(--text-muted); padding: 0.8rem; border-radius: 50px; cursor: pointer; transition: var(--transition);"
                onmouseover="this.style.borderColor='var(--text-main)'; this.style.color='var(--text-main)'"
                onmouseout="this.style.borderColor='var(--glass-border)'; this.style.color='var(--text-muted)'">
                Continue Shopping
            </button>
        </div>
    </div>
</div>

<script>
    document.querySelector('form[action="{{ route('cart.add', $product) }}"]').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Adding...';
        submitBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal();
                    // Optional: Update cart counter in header if it exists
                    // const cartCounter = document.getElementById('cart-count');
                    // if(cartCounter) cartCounter.innerText = data.cartCount;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            })
            .finally(() => {
                // Reset button
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
    });

    function showModal() {
        const modal = document.getElementById('successModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('successModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal on outside click
    document.getElementById('successModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Escape key to close
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.getElementById('successModal').style.display === 'flex') {
            closeModal();
        }
    });
</script>

<style>
    .size-radio:checked+.size-option {
        border-color: var(--primary);
        background: rgba(139, 92, 246, 0.1);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .size-option:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    #mainImage:hover {
        transform: scale(1.05);
    }
</style>
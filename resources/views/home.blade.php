<x-ramarama-layout>
    <section class="hero">
        <div class="hero-bg-subtle"></div>
        <div class="hero-container">
            <div class="hero-content animate-up">
                <div class="badge animate-up" style="animation-delay: 0.1s;">New Season Collection</div>
                <h1>A World Without<br><span class="text-gradient">Insecurities</span></h1>
                <p>Premium streetwear designed to empower your confidence. Express yourself without limits through
                    curated pieces that define the culture.</p>
                <div class="hero-actions animate-up" style="animation-delay: 0.3s;">
                    <a href="{{ route('products.index') }}" class="btn-primary">Explore Collections</a>
                    <a href="#featured" class="btn-outline">View Featured</a>
                </div>
            </div>

            <div class="hero-image animate-up" style="animation-delay: 0.4s;">
                <div class="hero-slider">
                    <img src="{{ asset('images/hero/hero_model_front.png') }}" class="slider-img active"
                        alt="Ramarama Hero Front">
                    <img src="{{ asset('images/hero/hero_model_back.png') }}" class="slider-img"
                        alt="Ramarama Hero Back">
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('.slider-img');
            let currentIndex = 0;

            if (images.length > 1) {
                setInterval(() => {
                    images[currentIndex].classList.remove('active');
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }, 3000);
            }
        });
    </script>

    <section class="section-featured" id="featured">
        <div class="section-header animate-up">
            <span class="sub-title">Featured Drops</span>
            <h2>Curated Pieces</h2>
            <p>Hand-selected items that represent the pinnacle of our design philosophy.</p>
        </div>

        <div class="carousel-container">
            <button class="carousel-btn prev" id="prevBtn" aria-label="Previous">
                <i class="fa-solid fa-arrow-left"></i>
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
                                    <div class="placeholder-image">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                <div class="card-overlay">
                                    <span class="view-tag">Quick View</span>
                                </div>
                            </div>
                            <div class="product-info">
                                <span class="category">Streetwear</span>
                                <h3>{{ $product->name }}</h3>
                                <div class="price-row">
                                    <span class="price">RM{{ number_format($product->price, 2) }}</span>
                                    @auth
                                        <div class="add-btn">
                                            <i class="fa-solid fa-plus"></i>
                                        </div>
                                    @else
                                        <div class="add-btn"
                                            style="background: rgba(255,255,255,0.05); color: var(--text-muted);">
                                            <i class="fa-solid fa-lock" style="font-size: 0.7rem;"></i>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <button class="carousel-btn next" id="nextBtn" aria-label="Next">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>

        @if(count($products) === 0)
            <div class="empty-state">
                <i class="fa-solid fa-box-open"></i>
                <p>New drops coming soon...</p>
                <a href="{{ route('products.index') }}" class="btn-link">Browse Full Shop</a>
            </div>
        @endif
    </section>

    <section class="section-philosophy">
        <div class="philosophy-content">
            <div class="phi-left animate-up">
                <span class="badge">Our Philosophy</span>
                <h2>Breaking the<br>Silence</h2>
            </div>
            <div class="phi-right animate-up" style="animation-delay: 0.2s;">
                <p>Ramarama co. was born from the belief that insecurity is a wall we all face. Our garments are more
                    than fabric; they are armor. Designed in the heart of KL, each piece is a statement of defiance
                    against the voices that tell you you're not enough.</p>
                <a href="#" class="btn-link">Read Our Story <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="phi-decoration">AWWI</div>
    </section>

    <section class="section-community">
        <div class="community-card animate-up">
            <div class="comm-content">
                <h2>Join the Movement</h2>
                <p>Stay updated with our latest drops, exclusive events, and community stories. No spam, just pure
                    culture.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit" class="btn-primary">Join Now</button>
                </form>
            </div>
            <div class="comm-bg"></div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            grid.addEventListener('scroll', handleInfiniteScroll);

            next.addEventListener('click', () => {
                grid.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            });

            prev.addEventListener('click', () => {
                grid.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
            });

            // Reveal animations on scroll
            const observerOptions = {
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-up').forEach(el => observer.observe(el));
        });
    </script>
</x-ramarama-layout>
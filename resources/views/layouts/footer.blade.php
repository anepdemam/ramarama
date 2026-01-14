<footer>
    <div class="footer-container">
        <div class="footer-section brand">
            <img src="{{ asset('images/global/logo.png') }}" alt="Ramarama Logo" class="footer-logo-img">
            <p>A World Without Insecurities. Premium streetwear designed to empower your journey.</p>
        </div>

        <div class="footer-section">
            <h4>Shop</h4>
            <ul class="footer-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('products.index') }}">Collections</a></li>
                <li><a href="{{ route('cart.index') }}">My Cart</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Support</h4>
            <ul class="footer-links">
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Shipping Policy</a></li>
                <li><a href="#">Returns & Exchanges</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

        <div class="footer-section contact">
            <h4>Get In Touch</h4>
            <p><i class="fa-solid fa-location-dot"></i> KL City Centre, Kuala Lumpur, Malaysia</p>
            <p><i class="fa-solid fa-envelope"></i> ramarama.awwi@gmail.com</p>
            <p><i class="fa-solid fa-phone"></i> +60 12-345 6789</p>
            <div class="social-links">
                <a href="https://www.instagram.com/ramarama.awwi/" target="_blank" aria-label="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://www.tiktok.com/@ramarama.co" target="_blank" aria-label="TikTok">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Ramarama. All Rights Reserved. A World Without Insecurities.</p>
    </div>
</footer>
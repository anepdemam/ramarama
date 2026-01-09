<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Ramarama.co') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="navbar" id="mainNavbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="logo">RAMARAMA</a>
            
            <nav>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('products.index') }}">Collections</a></li>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        @else
                            <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                    @endauth
                    <li>
                        <a href="{{ route('cart.index') }}" class="cart-icon">
                            <i class="fa-solid fa-cart-shopping"></i>
                            @if(Session::has('cart') && count(Session::get('cart')) > 0)
                                <span style="background: var(--primary); color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; position: relative; top: -10px; left: -5px;">{{ count(Session::get('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    @include('layouts.footer')
    @include('includes.chat_widget')

    <script>
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>

</html>
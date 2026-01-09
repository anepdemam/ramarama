<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Ramarama Store') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo-img" width="60" />
                @auth
                    <span class="greeting">Hi, {{ Auth::user()->username }}!</span>
                @endauth
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('products.index') }}">Products</a></li>
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    <li><a href="{{ route('orders.index') }}">History</a></li>
                    @auth
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    @include('layouts.footer')
    @include('includes.chat_widget')
</body>

</html>
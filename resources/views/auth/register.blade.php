<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Ramarama</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem;">
    
    <div style="position: absolute; width: 500px; height: 500px; background: var(--primary); filter: blur(200px); opacity: 0.15; top: -100px; left: -100px; border-radius: 50%;"></div>
    <div style="position: absolute; width: 400px; height: 400px; background: var(--accent); filter: blur(200px); opacity: 0.1; bottom: -100px; right: -100px; border-radius: 50%;"></div>

    <div class="glass" style="max-width: 450px; width: 100%; padding: 3rem; border-radius: 24px; position: relative; z-index: 10;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <a href="{{ url('/') }}" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2rem; font-weight: 800; background: linear-gradient(135deg, var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none;">
                RAMARAMA
            </a>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Create your account</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Name</label>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name"
                    style="width: 100%;"
                >
                @error('name')
                    <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Username -->
            <div style="margin-bottom: 1.5rem;">
                <label for="username" style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Username</label>
                <input 
                    id="username" 
                    type="text" 
                    name="username" 
                    value="{{ old('username') }}" 
                    required 
                    autocomplete="username"
                    style="width: 100%;"
                >
                @error('username')
                    <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Email</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email"
                    style="width: 100%;"
                >
                @error('email')
                    <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Password</label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    style="width: 100%;"
                >
                @error('password')
                    <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div style="margin-bottom: 2rem;">
                <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Confirm Password</label>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    style="width: 100%;"
                >
                @error('password_confirmation')
                    <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-user-plus"></i> Create Account
            </button>

            <!-- Links -->
            <div style="text-align: center;">
                <div style="padding-top: 1.5rem; border-top: 1px solid var(--glass-border);">
                    <span style="color: var(--text-muted); font-size: 0.9rem;">Already have an account?</span>
                    <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; margin-left: 0.5rem; transition: var(--transition);" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--primary)'">
                        Log in
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Ramarama</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem;">

    <div
        style="position: absolute; width: 500px; height: 500px; background: var(--primary); filter: blur(200px); opacity: 0.15; top: -100px; left: -100px; border-radius: 50%;">
    </div>

    <div class="glass"
        style="max-width: 450px; width: 100%; padding: 3rem; border-radius: 24px; position: relative; z-index: 10;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <a href="{{ url('/') }}"
                style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2rem; font-weight: 800; background: linear-gradient(135deg, var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none;">
                RAMARAMA
            </a>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Set your new password</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email -->
            <div style="margin-bottom: 1.5rem;">
                <label for="email"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                    autofocus autocomplete="username" style="width: 100%;">
                @error('email')
                    <span
                        style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div style="margin-bottom: 1.5rem;">
                <label for="password"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">New
                    Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    style="width: 100%;">
                @error('password')
                    <span
                        style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div style="margin-bottom: 2rem;">
                <label for="password_confirmation"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Confirm
                    Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password" style="width: 100%;">
                @error('password_confirmation')
                    <span
                        style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem;">
                <i class="fa-solid fa-key"></i> Reset Password
            </button>
        </form>
    </div>
</body>

</html>
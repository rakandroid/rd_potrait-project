<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - RD Potrait</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-auth-page min-h-screen text-[#f3f0ed] antialiased">
    <main class="admin-auth-shell">
        <section class="admin-login-card">
            <a class="admin-close-button" href="{{ url('/') }}" aria-label="Tutup login admin">
                &times;
            </a>
            <a href="{{ url('/') }}" class="brand-mark" aria-label="RD Potrait">
                <img src="{{ asset('images/rd-potrait-logo.png') }}" alt="RD Potrait">
            </a>
            <div class="mt-10">
                <p class="eyebrow">Admin Area</p>
                <h1 class="font-display text-4xl font-bold">Login Admin</h1>
                <p class="mt-4 leading-7 text-[#c9c1bd]">Masuk untuk mengelola foto portfolio di halaman depan.</p>
            </div>

            <form class="admin-login-form mt-8 space-y-5" method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-bold" for="email">Username Admin</label>
                    <input class="admin-input" id="email" name="email" type="text" value="{{ old('email') }}" placeholder="beniken" required autofocus>
                    @error('email')
                        <p class="mt-2 text-sm font-semibold text-[#ffb3b1]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold" for="password">Password</label>
                    <div class="password-field">
                        <input class="admin-input" id="password" name="password" type="password" required>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password" aria-pressed="false">Lihat</button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm font-semibold text-[#ffb3b1]">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                    <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="remember" value="1">
                    Ingat saya
                </label>

                <button class="admin-primary-button w-full" type="submit">
                    Masuk
                </button>
            </form>
        </section>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Profile fotografer RD Potrait. Kenali karakter visual, cara kerja, dan cerita di balik dokumentasi foto.">
    <title>Profile Fotografer - RD Potrait</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden bg-[#111111] text-[#f3f0ed] antialiased">
    <div class="portrait-scene" aria-hidden="true">
        <div class="portrait-scene-gradient"></div>
        <div class="portrait-scene-grid"></div>
        <div class="portrait-scene-vignette"></div>
        <div class="portrait-light portrait-light-a"></div>
        <div class="portrait-light portrait-light-b"></div>
        <div class="portrait-frame-lines"></div>
    </div>

    <header class="site-header fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-[#111111]/80 backdrop-blur-xl">
        <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 sm:px-8 lg:px-10" aria-label="Navigasi profile">
            <a href="{{ url('/') }}" class="brand-mark brand-mark--header" aria-label="RD Potrait">
                <img src="{{ asset('images/rd-potrait-logo-header.png') }}" alt="RD Potrait">
            </a>
            <div class="flex items-center gap-4 text-sm font-semibold text-[#d9d3cf]">
                <a class="nav-link" href="{{ url('/#portfolio') }}">Portfolio</a>
                <a class="border border-[#ffb3b1] px-5 py-3 font-bold text-[#ffb3b1] transition hover:bg-[#ffb3b1] hover:text-[#3b0509]" href="{{ url('/#kontak') }}">Booking</a>
            </div>
        </nav>
    </header>

    <main class="relative z-10 pt-20">
        <section class="profile-page-hero section-wrap">
            <div class="profile-page-heading reveal">
                <p class="eyebrow">Profile Fotografer</p>
                <h1 class="font-display font-bold text-white">Merekam rasa, bukan hanya rupa.</h1>
                <p>RD Potrait bekerja dengan pendekatan dokumenter yang tenang, personal, dan rapi secara visual agar setiap momen terasa hidup saat dilihat kembali.</p>
            </div>

            <div class="profile-page-grid">
                @foreach ($profiles as $profile)
                    <article class="profile-page-card reveal">
                        <div class="profile-page-photo">
                            <img src="{{ $profile->image_url }}" alt="{{ $profile->title }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                        </div>
                        <div class="profile-page-copy">
                            <p class="eyebrow">{{ $profile->category ?: 'Fotografer' }}</p>
                            <h2 class="font-display">{{ $profile->title }}</h2>
                            <p>{{ $profile->description }}</p>
                            <div class="profile-page-actions">
                                <a href="{{ url('/#kontak') }}">Booking</a>
                                <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener">Instagram</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </main>
</body>
</html>

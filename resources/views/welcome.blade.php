<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="R&D Photography adalah jasa fotografi dan videografi untuk wedding, pre-wedding, event, cinematic video, dan portrait. Booking R&D Potrait langsung via WhatsApp.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="R&D Photography - Wedding, Event, Portrait">
    <meta property="og:description" content="Jasa fotografi dan videografi untuk wedding, pre-wedding, event, cinematic video, dan portrait.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/rd-potrait-logo.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <title>R&D Photography - Wedding, Event, Portrait</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "LocalBusiness",
            "name": "R&D Photography",
            "url": "{{ url('/') }}",
            "image": "{{ asset('images/rd-potrait-logo.png') }}",
            "description": "Jasa fotografi dan videografi untuk wedding, pre-wedding, event, cinematic video, dan portrait.",
            "sameAs": [
                "https://www.instagram.com/rd_potrait?igsh=MXA4NDV0emJjN2Nsag==",
                "https://www.tiktok.com/@r.benidarmansyah?lang=en"
            ]
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden bg-[#111111] text-[#f3f0ed] antialiased" data-open-admin-modal="{{ ($errors->has('email') || $errors->has('password')) ? 'true' : 'false' }}">
    <div class="portrait-scene" aria-hidden="true">
        <div class="portrait-scene-gradient"></div>
        <div class="portrait-scene-grid"></div>
        <div class="portrait-scene-vignette"></div>
        <div class="portrait-light portrait-light-a"></div>
        <div class="portrait-light portrait-light-b"></div>
        <div class="portrait-frame-lines"></div>
    </div>

    <header class="site-header fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-[#111111]/80 backdrop-blur-xl" data-site-header>
        <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 sm:px-8 lg:px-10" aria-label="Navigasi utama">
            <a href="#home" class="brand-mark brand-mark--header" aria-label="R&D Photography">
                <img src="{{ asset('images/rd-potrait-logo-header.png') }}" alt="R&D Photography">
            </a>

            <div class="hidden items-center gap-8 text-sm font-semibold text-[#d9d3cf] lg:flex">
                <a class="nav-link" href="#layanan">Layanan</a>
                <a class="nav-link" href="#portfolio">Portfolio</a>
                <a class="nav-link" href="#proses">Proses</a>
                <a class="nav-link" href="#testimoni">Testimoni</a>
                <a class="nav-link" href="{{ route('admin.login') }}" data-admin-modal-open>Admin</a>
            </div>

            <a class="hidden border border-[#ffb3b1] px-5 py-3 text-sm font-bold text-[#ffb3b1] transition hover:bg-[#ffb3b1] hover:text-[#3b0509] lg:inline-flex" href="#kontak">
                Booking
            </a>

            <button class="inline-flex h-11 w-11 items-center justify-center border border-white/15 text-[#f3f0ed] lg:hidden" type="button" data-menu-toggle aria-controls="mobile-menu" aria-expanded="false">
                <span class="sr-only">Buka menu</span>
                <span class="menu-line"></span>
            </button>
        </nav>

        <div id="mobile-menu" class="hidden border-t border-white/10 bg-[#151515] px-5 py-5 lg:hidden">
            <div class="flex flex-col gap-4 text-sm font-semibold text-[#d9d3cf]">
                <a href="#layanan">Layanan</a>
                <a href="#portfolio">Portfolio</a>
                <a href="#proses">Proses</a>
                <a href="#testimoni">Testimoni</a>
                <a href="{{ route('admin.login') }}" data-admin-modal-open>Admin</a>
                <a class="mt-2 border border-[#ffb3b1] px-5 py-3 text-center text-[#ffb3b1]" href="#kontak">Booking</a>
            </div>
        </div>
    </header>

    <main id="home" class="relative z-10">
        <section class="portrait-hero relative min-h-[92vh] overflow-hidden pt-20">
            <div class="hero-bg-sequence absolute inset-0" style="--hero-slide-duration: {{ max($heroPhotos->count(), 3) * 4 }}s;" aria-hidden="true">
                @foreach ($heroPhotos as $photo)
                    <span class="hero-bg-photo" style="background-image: url('{{ $photo->image_url }}'); animation-delay: {{ $loop->index * 4 }}s;"></span>
                @endforeach
            </div>
            <div class="hero-ambient absolute inset-0"></div>
            <div class="absolute inset-0 z-[2] bg-[linear-gradient(90deg,rgba(8,8,8,0.92)_0%,rgba(8,8,8,0.82)_38%,rgba(17,17,17,0.32)_58%,rgba(17,17,17,0.12)_100%)]"></div>
            <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-b from-transparent to-[#111111]"></div>
            <div class="hero-layout relative mx-auto grid min-h-[calc(92vh-80px)] max-w-7xl items-center gap-12 px-5 py-16 sm:px-8 lg:grid-cols-[0.92fr_1.08fr] lg:px-10">
                <div class="hero-copy max-w-3xl reveal">
                    <span class="hero-copy-accent" aria-hidden="true"></span>
                    <p class="mb-5 text-sm font-bold uppercase text-[#ffb3b1]">Portrait, Wedding, Event</p>
                    <h1 class="hero-title font-display text-3xl font-bold leading-[1.12] text-white sm:text-4xl lg:text-3xl">Membajak momen dari waktu, menyimpannya dalam bentuk rindu.</h1>
                    <p class="hero-lede mt-7 max-w-2xl text-lg font-medium leading-8 text-[#f0e8e4]">"Dunia bergerak terlalu cepat, dan ingatan manusia sering kali mengkhianati waktu. Tugas kami adalah menghentikan waktu tersebut tepat di momen terbaiknya. Tanpa skenario, tanpa kepura-puraan. Kami tidak hanya menangkap sebuah gambar, tapi sebuah jiwa. Kami merekam esensi dari sebuah momen, dinamika jalanan, dan detak kehidupan yang jujur. Ketika Anda melihat kembali foto-foto ini bertahun-tahun kemudian, Anda tidak hanya mengingat apa yang terjadi, tetapi siapa Anda saat itu".</p>
                    <div class="hero-actions mt-10 flex flex-col gap-4 sm:flex-row">
                        <a class="inline-flex justify-center bg-[#ffb3b1] px-7 py-4 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3]" href="#portfolio">Lihat Portfolio</a>
                        <a class="inline-flex justify-center border border-white/20 px-7 py-4 font-bold text-white transition hover:border-white hover:bg-white/10" href="#layanan">Pilih Layanan</a>
                    </div>
                </div>

                <div class="hero-showcase reveal" aria-hidden="true">
                    <div class="hero-burst"></div>
                    <div class="hero-screen">
                        <div class="hero-screen-topline">
                            <span>R&D</span>
                            <span>PORTRAIT 2026</span>
                        </div>
                        <div class="hero-photo-sequence" style="--hero-slide-duration: {{ max($heroPhotos->count(), 3) * 4 }}s;">
                            @foreach ($heroPhotos as $photo)
                                <span class="hero-photo" style="background-image: url('{{ $photo->image_url }}'); animation-delay: {{ $loop->index * 4 }}s;"></span>
                            @endforeach
                        </div>
                        <div class="hero-focus-ring"></div>
                        <div class="hero-screen-grid"></div>
                    </div>
                    <div class="hero-console"></div>
                    <div class="hero-reflection"></div>
                </div>
            </div>
        </section>

        <section id="layanan" class="scene-section section-wrap">
            <div class="section-heading reveal">
                <p class="eyebrow">Layanan</p>
                <h2>Paket visual untuk momen yang berbeda.</h2>
                <p>Klik layanan untuk melihat detail package, durasi, benefit, dan harga.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['wedding', 'Wedding Photography', 'Dokumentasi akad, resepsi, candid keluarga, dan portrait editorial.'],
                    ['prewedding', 'Pre-Wedding', 'Konsep personal, moodboard, arahan pose, dan pilihan lokasi.'],
                    ['cinematic', 'Cinematic Video', 'Highlight film, semi dokumenter, dan color grading.'],
                    ['event', 'Portrait & Event', 'Sesi studio, company profile, wisuda, ulang tahun, dan acara khusus.'],
                ] as $service)
                    <button class="service-card photo-frame reveal border border-white/10 bg-[#191919] p-6 text-left transition hover:border-[#ffb3b1]/60" type="button" data-service-open="{{ $service[0] }}">
                        <div class="mb-10 h-1 w-12 bg-[#ffb3b1]"></div>
                        <h3 class="font-display text-2xl font-bold">{{ $service[1] }}</h3>
                        <p class="mt-4 leading-7 text-[#c9c1bd]">{{ $service[2] }}</p>
                        <span class="mt-8 inline-flex border border-white/15 px-4 py-2 text-sm font-bold text-[#ffb3b1]">Lihat Pricelist</span>
                    </button>
                @endforeach
            </div>
        </section>

        <div class="price-modal fixed inset-0 z-[70] hidden items-center justify-center px-5 py-8" data-price-modal aria-hidden="true">
            <button class="absolute inset-0 bg-[#060606]/82 backdrop-blur-md" type="button" data-price-modal-close aria-label="Tutup pricelist"></button>
            <section class="price-modal-panel relative w-full max-w-5xl border border-white/10 bg-[#151515] shadow-2xl shadow-black/60" data-price-modal-panel>
                <button class="price-modal-close absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center border border-white/15 text-xl leading-none text-white transition hover:bg-white/10" type="button" data-price-modal-close aria-label="Tutup pricelist">
                    &times;
                </button>
                <div class="price-modal-visual" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="price-modal-body relative grid gap-7 p-6 md:grid-cols-[0.62fr_1.38fr] md:p-8">
                    <div>
                        <p class="eyebrow" data-price-eyebrow>Pricelist</p>
                        <h2 class="font-display text-4xl font-bold leading-tight" data-price-title></h2>
                        <p class="mt-4 leading-7 text-[#c9c1bd]" data-price-description></p>
                        <a class="mt-7 inline-flex bg-[#ffb3b1] px-6 py-3 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3]" href="#" target="_blank" rel="noopener" data-price-wa>
                            Tanya via WhatsApp
                        </a>
                    </div>
                    <div class="price-package-list grid gap-4" data-price-packages></div>
                </div>
            </section>
        </div>

        <section id="portfolio" class="scene-section section-wrap pt-0">
            <div class="section-heading reveal">
                <p class="eyebrow">Portfolio</p>
                <h2>Galeri R&D Potrait</h2>
            </div>

            @php
                $videoPoster = $portfolioPhotos->first()?->image_url ?? asset('images/portfolio/beni-profile-camera.jpeg');
                $showcaseMedia = $portfolioPhotos->take(6);
                $videoMimeType = fn (string $url) => match (strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION))) {
                    'webm' => 'video/webm',
                    'mov' => 'video/quicktime',
                    default => 'video/mp4',
                };
            @endphp

            @if ($showcaseMedia->isNotEmpty())
                <div class="portfolio-showcase reveal" style="--portfolio-slide-duration: {{ max($showcaseMedia->count(), 3) * 4 }}s;">
                    <div class="portfolio-showcase-stage">
                        @foreach ($showcaseMedia as $media)
                            <figure class="portfolio-showcase-slide" style="animation-delay: {{ $loop->index * 4 }}s;">
                                <img src="{{ $media->image_url }}" alt="{{ $media->title }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                                <figcaption>
                                    <span>{{ $media->title }}</span>
                                    <small>{{ $media->category }}</small>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                    <div class="portfolio-showcase-rail" aria-hidden="true">
                        @foreach ($showcaseMedia as $media)
                            <span style="animation-delay: {{ $loop->index * 4 }}s;"></span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="portfolio-video-feature mb-8 grid gap-5 {{ $portfolioVideos->isNotEmpty() ? 'lg:grid-cols-[1.35fr_0.65fr]' : 'lg:grid-cols-1' }}">
                @if ($portfolioVideos->isNotEmpty())
                    <div class="portfolio-video-list {{ $portfolioVideos->count() === 1 ? 'portfolio-video-list--single' : '' }} grid gap-5 {{ $portfolioVideos->count() > 1 ? 'md:grid-cols-2 lg:grid-cols-1' : '' }}">
                        @foreach ($portfolioVideos as $video)
                            <figure class="portfolio-video reveal">
                                <video controls preload="none" playsinline poster="{{ $videoPoster }}" data-focus-video>
                                    <source src="{{ $video->image_url }}" type="{{ $videoMimeType($video->image_url) }}">
                                </video>
                                <figcaption>
                                    <span>{{ $video->title }}</span>
                                    <small>{{ $video->category }}</small>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                @endif

                    <div class="portfolio-character-list grid gap-5">
                        @foreach ($profiles as $profile)
                            <aside class="portfolio-character reveal {{ $portfolioVideos->isEmpty() ? 'portfolio-character--standalone' : '' }}" aria-label="Profil {{ $profile->title }}">
                                <div class="portfolio-character-visual" aria-hidden="true">
                                    @if ($profile->image_url)
                                        <div class="portfolio-character-slider">
                                            <img src="{{ $profile->image_url }}" alt="" loading="lazy" decoding="async">
                                        </div>
                                    @else
                                        <span>{{ collect(explode(' ', $profile->title))->map(fn ($word) => mb_substr($word, 0, 1))->take(2)->join('') }}</span>
                                    @endif
                                </div>
                                <div class="portfolio-character-copy">
                                    <p class="eyebrow">{{ $profile->category ?: 'Profile' }}</p>
                                    <h4>{{ $profile->title }}</h4>
                                    <p>{{ $profile->description }}</p>
                                </div>
                            </aside>
                        @endforeach
                    </div>
            </div>

            <div class="portfolio-grid grid auto-rows-[280px] gap-5 md:grid-cols-4">
                @forelse ($portfolioPhotos as $photo)
                    <figure class="portfolio-tile photo-print reveal {{ $loop->first ? 'md:col-span-2 md:row-span-2' : ($loop->iteration % 4 === 0 ? 'md:col-span-2' : '') }}">
                        <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" loading="lazy" decoding="async">
                        <figcaption>{{ $photo->title }} <span class="block text-sm font-semibold text-[#c9c1bd]">{{ $photo->category }}</span></figcaption>
                    </figure>
                @empty
                    <div class="reveal border border-white/10 bg-[#191919] p-8 text-[#c9c1bd] md:col-span-4">
                        Portfolio belum tersedia. Silakan tambahkan foto dari halaman admin.
                    </div>
                @endforelse
            </div>
        </section>

        <section id="proses" class="scene-section section-wrap bg-[#171717]/85 backdrop-blur-sm">
            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div class="section-heading reveal lg:sticky lg:top-28">
                    <p class="eyebrow">Proses</p>
                    <h2>Alur kerja sederhana dari konsultasi sampai delivery.</h2>
                    <p>konsultasi pra acara dari awal sampai akhir bisa di kondisikan</p>
                </div>

                <div class="space-y-5">
                    @foreach ([
                        ['01', 'Konsultasi', 'Memahami tanggal, lokasi, konsep, dan kebutuhan output.'],
                        ['02', 'Moodboard', 'Menyusun referensi gaya foto, warna, wardrobe, dan rundown.'],
                        ['03', 'Produksi', 'Pengambilan gambar dengan arahan natural dan komposisi rapi.'],
                        ['04', 'Editing & Delivery', 'Kurasi, retouching, color grading, lalu pengiriman galeri online.'],
                    ] as $step)
                        <article class="shutter-card reveal grid gap-4 border border-white/10 bg-[#111111] p-6 sm:grid-cols-[80px_1fr]">
                            <span class="font-display text-4xl font-bold text-[#ffb3b1]/45">{{ $step[0] }}</span>
                            <div>
                                <h3 class="font-display text-2xl font-bold">{{ $step[1] }}</h3>
                                <p class="mt-3 leading-7 text-[#c9c1bd]">{{ $step[2] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="testimoni" class="scene-section section-wrap">
            <div class="section-heading reveal">
                <p class="eyebrow">Testimoni</p>
                <h2>Cerita klien dan rating dari audiens.</h2>
            </div>

            @php
                $defaultTestimonials = collect([
                    (object) ['name' => 'Adinda & Pratama', 'message' => 'Hasilnya terasa personal, hangat, dan tetap mewah. Momen kecil yang kami hampir lupa justru tertangkap sangat indah.', 'rating' => 5],
                    (object) ['name' => 'Sarah & Kevin', 'message' => 'Arahannya nyaman sekali. Kami yang biasanya kaku di depan kamera bisa terlihat natural.', 'rating' => 5],
                    (object) ['name' => 'Maya & Rizky', 'message' => 'Delivery cepat, editing konsisten, dan semua file tersusun rapi. Sangat profesional.', 'rating' => 5],
                ]);
                $testimonials = $audienceTestimonials->concat($defaultTestimonials)->take(9);
            @endphp

            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-start">
                <div class="grid gap-5 md:grid-cols-2">
                    @foreach ($testimonials as $quote)
                        <figure class="testimonial-card photo-frame reveal border border-white/10 bg-[#191919] p-7">
                            <div class="testimonial-stars" aria-label="{{ $quote->rating }} dari 5 bintang">
                                @for ($star = 1; $star <= 5; $star++)
                                    <span class="{{ $star <= $quote->rating ? 'is-active' : '' }}">&#9733;</span>
                                @endfor
                            </div>
                            <blockquote class="mt-5 text-lg leading-8 text-[#e7dfdb]">"{{ $quote->message }}"</blockquote>
                            <figcaption class="mt-8 font-bold text-[#ffb3b1]">{{ $quote->name }}</figcaption>
                        </figure>
                    @endforeach
                </div>

                <form class="testimonial-form form-panel reveal p-7" method="POST" action="{{ route('testimonials.store') }}">
                    @csrf
                    <p class="mb-3 text-sm font-bold uppercase text-[#ffb3b1]">Rate R&D Potrait</p>
                    <h3 class="font-display text-3xl font-bold text-white">Bagikan pengalaman kamu.</h3>

                    @if (session('testimonial_success'))
                        <p class="mt-5 border border-[#ffb3b1]/30 bg-[#2a1718] px-4 py-3 text-sm font-bold text-[#ffd6d3]">{{ session('testimonial_success') }}</p>
                    @endif

                    <div class="mt-6">
                        <label class="mb-2 block text-sm font-bold" for="testimonial-name">Nama</label>
                        <input class="testimonial-input" id="testimonial-name" name="testimonial_name" type="text" value="{{ old('testimonial_name') }}" required>
                        @error('testimonial_name')
                            <p class="mt-2 text-sm font-bold text-[#9f1324]">{{ $message }}</p>
                        @enderror
                    </div>

                    <fieldset class="mt-5">
                        <legend class="mb-3 text-sm font-bold">Rating</legend>
                        <div class="rating-picker" data-rating-picker>
                            @for ($rating = 5; $rating >= 1; $rating--)
                                <input id="rating-{{ $rating }}" name="rating" type="radio" value="{{ $rating }}" @checked((int) old('rating', 5) === $rating) required>
                                <label for="rating-{{ $rating }}" aria-label="{{ $rating }} bintang">&#9733;</label>
                            @endfor
                        </div>
                        <p class="mt-2 text-sm font-bold text-[#9f1324]" data-rating-text>{{ old('rating', 5) }} dari 5 bintang</p>
                        @error('rating')
                            <p class="mt-2 text-sm font-bold text-[#9f1324]">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <div class="mt-5">
                        <label class="mb-2 block text-sm font-bold" for="testimonial-message">Testimoni</label>
                        <textarea class="testimonial-input min-h-32" id="testimonial-message" name="testimonial_message" required>{{ old('testimonial_message') }}</textarea>
                        @error('testimonial_message')
                            <p class="mt-2 text-sm font-bold text-[#9f1324]">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="mt-6 w-full bg-[#111111] px-7 py-4 font-bold text-white transition hover:bg-[#9f1324]" type="submit">
                        Kirim Testimoni
                    </button>
                </form>
            </div>
        </section>

        <section id="kontak" class="relative px-5 pb-20 sm:px-8 lg:px-10">
            <div class="form-panel mx-auto grid max-w-7xl gap-8 p-7 md:p-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
                <div class="reveal">
                    <p class="mb-3 text-sm font-bold uppercase text-[#ffb3b1]">Booking Sekarang</p>
                    <h2 class="font-display text-3xl font-bold text-white sm:text-4xl">Siap jadwalkan sesi foto berikutnya?</h2>
                    <p class="mt-4 max-w-2xl leading-7 text-[#d9d3cf]">Isi data singkat, lalu sistem akan membuka WhatsApp dengan format pesan booking otomatis.</p>
                </div>
                <form class="reveal grid gap-4 sm:grid-cols-2" method="POST" action="{{ route('booking.store') }}">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-bold" for="booking-name">Nama</label>
                        <input class="booking-input" id="booking-name" name="name" type="text" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="mt-2 text-sm font-bold text-[#9f1324]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold" for="booking-phone">No WhatsApp</label>
                        <input class="booking-input" id="booking-phone" name="phone" type="text" value="{{ old('phone') }}" required>
                        @error('phone')
                            <p class="mt-2 text-sm font-bold text-[#9f1324]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold" for="booking-service">Layanan</label>
                        <select class="booking-input" id="booking-service" name="service" required>
                            @foreach (['Wedding Photography', 'Pre-Wedding', 'Cinematic Video', 'Portrait & Event'] as $service)
                                <option value="{{ $service }}" @selected(old('service') === $service)>{{ $service }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold" for="booking-date">Tanggal Acara</label>
                        <input class="booking-input" id="booking-date" name="event_date" type="date" value="{{ old('event_date') }}">
                        <p class="mt-2 text-sm font-bold text-[#ffb3b1]" data-date-preview>Pilih tanggal acara</p>
                        @error('event_date')
                            <p class="mt-2 text-sm font-bold text-[#9f1324]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-bold" for="booking-location">Lokasi</label>
                        <div class="location-field">
                            <input class="booking-input" id="booking-location" name="location" type="text" value="{{ old('location') }}" placeholder="Nama venue / alamat / link Google Maps">
                            <button class="location-button" type="button" data-location-button>Pakai Lokasi Saya</button>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-[#c9c1bd]" data-location-status>Lokasi bisa diisi manual atau otomatis dari Google Maps.</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-bold" for="booking-message">Catatan</label>
                        <textarea class="booking-input min-h-28" id="booking-message" name="message">{{ old('message') }}</textarea>
                    </div>
                    <button class="bg-[#111111] px-7 py-4 font-bold text-white transition hover:bg-[#9f1324] sm:col-span-2" type="submit">
                        Booking via WhatsApp
                    </button>
                </form>
            </div>
        </section>
    </main>

    <div class="admin-modal fixed inset-0 z-[80] hidden items-center justify-center px-5 py-8" data-admin-modal aria-hidden="true">
        <button class="absolute inset-0 bg-[#060606]/80 backdrop-blur-md" type="button" data-admin-modal-close aria-label="Tutup login admin"></button>
        <section class="admin-modal-panel relative w-full max-w-md border border-white/10 bg-[#191919] p-7 shadow-2xl shadow-black/50">
                <button class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center border border-white/15 text-xl leading-none text-white transition hover:bg-white/10" type="button" data-admin-modal-close aria-label="Tutup login admin">
                    &times;
                </button>
            <a href="{{ url('/') }}" class="brand-mark" aria-label="R&D Photography">
                <img src="{{ asset('images/rd-potrait-logo.png') }}" alt="R&D Photography">
            </a>
            <div class="mt-10">
                <p class="eyebrow">Admin Area</p>
                <h2 class="font-display text-4xl font-bold">Login Admin</h2>
                <p class="mt-4 leading-7 text-[#c9c1bd]">Halo R&D potrait selamat datang kembali</p>
            </div>

            <form class="mt-8 space-y-5" method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-bold" for="modal-email">Username </label>
                    <input class="admin-input" id="modal-email" name="email" type="text" value="{{ old('email') }}" placeholder="beniken" required>
                    @error('email')
                        <p class="mt-2 text-sm font-semibold text-[#ffb3b1]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold" for="modal-password">Password</label>
                    <div class="password-field">
                        <input class="admin-input" id="modal-password" name="password" type="password" required>
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

                <button class="w-full bg-[#ffb3b1] px-6 py-4 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3]" type="submit">
                    Masuk
                </button>

                <button class="w-full border border-white/15 px-6 py-3 font-bold text-white transition hover:bg-white/10" type="button" data-admin-modal-close>
                    Tutup
                </button>
            </form>
        </section>
    </div>

    <div class="site-audio" data-audio-widget>
        <audio src="{{ asset('audio/until-i-found-you-violin-cover.mp3') }}" loop preload="none" data-background-audio></audio>
        <button class="site-audio-toggle" type="button" data-audio-toggle aria-pressed="false">
            Nyalakan lagu
        </button>
    </div>

    <footer class="relative z-10 border-t border-white/10 bg-[#0d0d0d] px-5 py-10 sm:px-8 lg:px-10">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 text-sm text-[#bdb4b0] md:flex-row md:items-center md:justify-between">
            <p class="brand-mark text-white">
                <img src="{{ asset('images/rd-potrait-logo.png') }}" alt="R&D Photography">
                <span>R&D Photography</span>
            </p>
            <p>© {{ date('Y') }} R&D Photography. All rights reserved.</p>
            <div class="flex gap-5">
                <a class="hover:text-[#ffb3b1]" href="https://www.instagram.com/rd_potrait?igsh=MXA4NDV0emJjN2Nsag==" target="_blank" rel="noopener">Instagram</a>
                <a class="hover:text-[#ffb3b1]" href="https://www.tiktok.com/@r.benidarmansyah?lang=en" target="_blank" rel="noopener">TikTok</a>
            </div>
        </div>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - RD Potrait</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#111111] text-[#f3f0ed] antialiased">
    <header class="border-b border-white/10 bg-[#151515]">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-5 py-5 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10">
            <div>
                <a href="{{ url('/') }}" class="brand-mark" aria-label="RD Potrait">
                    <img src="{{ asset('images/rd-potrait-logo.png') }}" alt="RD Potrait">
                    <span>RD Potrait</span>
                </a>
                <p class="mt-1 text-sm text-[#bdb4b0]">Dashboard foto welcome, portfolio, audio, dan jadwal booked</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a class="border border-white/15 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/10" href="{{ url('/') }}" target="_blank">Lihat Website</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="bg-[#ffb3b1] px-4 py-3 text-sm font-bold text-[#3b0509] transition hover:bg-[#ffd6d3]" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8 lg:px-10">
        @if (session('status'))
            <div class="mb-6 border border-[#ffb3b1]/50 bg-[#2a1718] px-5 py-4 font-semibold text-[#ffd6d3]">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 border border-[#ffb3b1]/50 bg-[#2a1718] px-5 py-4 text-[#ffd6d3]">
                <p class="font-bold">Ada data yang perlu dicek:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="mb-10 border border-white/10 bg-[#151515] p-6">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow">Website</p>
                    <h1 class="font-display text-3xl font-bold">Backsound dan Sosial Media</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#bdb4b0]">Upload lagu baru dan atur link Instagram/TikTok yang muncul di tombol audio website.</p>
                </div>
            </div>

            <form class="grid gap-4 border border-white/10 bg-[#191919] p-5 md:grid-cols-3" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div>
                    <label class="admin-label" for="backsound">Ganti Backsound</label>
                    <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-4 file:py-2 file:font-bold file:text-[#3b0509]" id="backsound" name="backsound" type="file" accept="audio/*">
                    <p class="mt-2 text-xs font-semibold text-[#bdb4b0]">File saat ini: {{ basename($settings->backsound_path ?? 'audio default') }}</p>
                </div>
                <div>
                    <label class="admin-label" for="instagram_url">Instagram</label>
                    <input class="admin-input" id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url', $settings->instagram_url) }}">
                </div>
                <div>
                    <label class="admin-label" for="tiktok_url">TikTok</label>
                    <input class="admin-input" id="tiktok_url" name="tiktok_url" type="url" value="{{ old('tiktok_url', $settings->tiktok_url) }}">
                </div>
                <button class="bg-[#ffb3b1] px-6 py-4 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3] md:col-span-3" type="submit">Simpan Pengaturan</button>
            </form>
        </section>

        <section class="mb-10 border border-white/10 bg-[#151515] p-6">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow">Kalender Admin</p>
                    <h1 class="font-display text-3xl font-bold">Label Tanggal Booked</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#bdb4b0]">Tandai jadwal sebagai booked agar tanggal yang sama tidak bentrok dari form booking publik.</p>
                </div>
                <p class="text-sm font-semibold text-[#bdb4b0]">{{ $bookings->count() }} jadwal</p>
            </div>

            <div class="booking-calendar-grid">
                @forelse ($bookings as $booking)
                    <article class="booking-calendar-card" style="--booking-color: {{ $booking->color ?: '#ffb3b1' }}">
                        <div>
                            <p class="booking-calendar-date">{{ $booking->event_date?->format('d M Y') }}</p>
                            <h2>{{ $booking->label ?: $booking->service }}</h2>
                            <p>{{ $booking->name }} - {{ $booking->phone }}</p>
                            <p>{{ $booking->location ?: 'Lokasi belum diisi' }}</p>
                        </div>
                        <form class="mt-5 grid gap-3" method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                            @csrf
                            @method('PUT')
                            <input class="admin-input" name="label" type="text" value="{{ old('label', $booking->label) }}" placeholder="Label, contoh: Wedding Dina">
                            <select class="admin-input" name="status" required>
                                @foreach (['pending' => 'Pending', 'booked' => 'Booked', 'done' => 'Selesai', 'cancelled' => 'Batal'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <input class="admin-input" name="color" type="color" value="{{ old('color', $booking->color ?: '#ffb3b1') }}">
                            <button class="border border-[#ffb3b1] px-4 py-3 font-bold text-[#ffb3b1] transition hover:bg-[#ffb3b1] hover:text-[#3b0509]" type="submit">Update Jadwal</button>
                        </form>
                        <form class="mt-3" method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="w-full border border-white/15 px-4 py-3 font-bold text-white transition hover:bg-white/10" type="submit">Hapus Jadwal</button>
                        </form>
                    </article>
                @empty
                    <div class="border border-white/10 bg-[#191919] p-8 text-[#c9c1bd]">
                        Belum ada booking bertanggal.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="mb-10 border border-white/10 bg-[#151515] p-6">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow">Keamanan</p>
                    <h1 class="font-display text-3xl font-bold">Ubah Password Admin</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#bdb4b0]">Gunakan password baru minimal 8 karakter.</p>
                </div>
            </div>

            <form class="grid gap-4 border border-white/10 bg-[#191919] p-5 md:grid-cols-3" method="POST" action="{{ route('admin.password.update') }}">
                @csrf
                @method('PUT')
                <div>
                    <label class="admin-label" for="current-password">Password Saat Ini</label>
                    <div class="password-field">
                        <input class="admin-input" id="current-password" name="current_password" type="password" required autocomplete="current-password">
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password" aria-pressed="false">Lihat</button>
                    </div>
                </div>
                <div>
                    <label class="admin-label" for="new-password">Password Baru</label>
                    <div class="password-field">
                        <input class="admin-input" id="new-password" name="password" type="password" required autocomplete="new-password">
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password" aria-pressed="false">Lihat</button>
                    </div>
                </div>
                <div>
                    <label class="admin-label" for="new-password-confirmation">Ulangi Password Baru</label>
                    <div class="password-field">
                        <input class="admin-input" id="new-password-confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password" aria-pressed="false">Lihat</button>
                    </div>
                </div>
                <button class="bg-[#ffb3b1] px-6 py-4 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3] md:col-span-3" type="submit">Simpan Password Baru</button>
            </form>
        </section>

        <section class="mb-10 border border-white/10 bg-[#151515] p-6">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow">Slide Welcome</p>
                    <h1 class="font-display text-3xl font-bold">Kelola Foto Slider Awal</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#bdb4b0]">Foto ini tampil di layar kamera pada bagian awal website. Urutan kecil tampil lebih dulu.</p>
                </div>
                <p class="text-sm font-semibold text-[#bdb4b0]">{{ $heroPhotos->count() }} foto</p>
            </div>

            <form class="mb-6 grid gap-4 border border-white/10 bg-[#191919] p-5 md:grid-cols-[1fr_1fr_120px_auto]" method="POST" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="placement" value="hero">
                <div>
                    <label class="admin-label" for="hero-title">Judul Slide</label>
                    <input class="admin-input" id="hero-title" name="title" type="text" value="{{ old('title') }}" placeholder="Wedding Moment" required>
                </div>
                <div>
                    <label class="admin-label" for="hero-image">Foto Slide</label>
                    <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-4 file:py-2 file:font-bold file:text-[#3b0509]" id="hero-image" name="image" type="file" accept="image/*" data-max-upload-bytes="3670016" data-crop-upload required>
                </div>
                <div>
                    <label class="admin-label" for="hero-sort">Urutan</label>
                    <input class="admin-input" id="hero-sort" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}">
                </div>
                <input type="hidden" name="category" value="Hero">
                <input type="hidden" name="is_visible" value="1">
                <button class="self-end bg-[#ffb3b1] px-6 py-4 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3]" type="submit">Tambah Slide</button>
            </form>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($heroPhotos as $photo)
                    <article class="border border-white/10 bg-[#191919]">
                        <img class="h-56 w-full object-cover" src="{{ $photo->image_url }}" alt="{{ $photo->title }}">
                        <form class="space-y-4 p-5" method="POST" action="{{ route('admin.portfolio.update', $photo) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="placement" value="hero">
                            <input type="hidden" name="category" value="Hero">
                            <div>
                                <label class="admin-label" for="hero-title-{{ $photo->id }}">Judul Slide</label>
                                <input class="admin-input" id="hero-title-{{ $photo->id }}" name="title" type="text" value="{{ old('title', $photo->title) }}" required>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-[1fr_110px]">
                                <div>
                                    <label class="admin-label" for="hero-image-{{ $photo->id }}">Ganti Foto</label>
                                    <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-3 file:py-2 file:font-bold file:text-[#3b0509]" id="hero-image-{{ $photo->id }}" name="image" type="file" accept="image/*" data-max-upload-bytes="3670016" data-crop-upload>
                                </div>
                                <div>
                                    <label class="admin-label" for="hero-sort-{{ $photo->id }}">Urutan</label>
                                    <input class="admin-input" id="hero-sort-{{ $photo->id }}" name="sort_order" type="number" min="0" value="{{ old('sort_order', $photo->sort_order) }}">
                                </div>
                            </div>
                            <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                                <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="is_visible" value="1" @checked($photo->is_visible)>
                                Tampilkan di slider
                            </label>
                            <button class="w-full border border-[#ffb3b1] px-4 py-3 font-bold text-[#ffb3b1] transition hover:bg-[#ffb3b1] hover:text-[#3b0509]" type="submit">Update Slide</button>
                        </form>
                        <form class="px-5 pb-5" method="POST" action="{{ route('admin.portfolio.destroy', $photo) }}" onsubmit="return confirm('Hapus slide ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="w-full border border-white/15 px-4 py-3 font-bold text-white transition hover:bg-white/10" type="submit">Hapus Slide</button>
                        </form>
                    </article>
                @empty
                    <div class="border border-white/10 bg-[#191919] p-8 text-[#c9c1bd] md:col-span-2 lg:col-span-3">
                        Belum ada slide welcome. Kalau kosong, website memakai foto fallback bawaan.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="mb-10 border border-white/10 bg-[#151515] p-6">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow">Rating Audiens</p>
                    <h1 class="font-display text-3xl font-bold">Kelola Testimoni</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#bdb4b0]">Rating baru masuk sebagai pending. Approve rating agar tampil di halaman depan.</p>
                </div>
                <p class="text-sm font-semibold text-[#bdb4b0]">{{ $testimonials->count() }} rating</p>
            </div>

            <div class="grid gap-5 lg:grid-cols-2">
                @forelse ($testimonials as $testimonial)
                    <article class="border border-white/10 bg-[#191919] p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="testimonial-stars" aria-label="{{ $testimonial->rating }} dari 5 bintang">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <span class="{{ $star <= $testimonial->rating ? 'is-active' : '' }}">&#9733;</span>
                                    @endfor
                                </div>
                                <h2 class="mt-4 font-display text-2xl font-bold">{{ $testimonial->name }}</h2>
                                <p class="mt-2 text-xs font-bold uppercase {{ $testimonial->is_visible ? 'text-[#ffb3b1]' : 'text-[#bdb4b0]' }}">
                                    {{ $testimonial->is_visible ? 'Tampil di halaman depan' : 'Pending approve' }}
                                </p>
                            </div>
                            <p class="text-sm font-semibold text-[#bdb4b0]">{{ $testimonial->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <blockquote class="mt-5 leading-7 text-[#d9d3cf]">"{{ $testimonial->message }}"</blockquote>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
                                @csrf
                                @method('PUT')
                                <button class="w-full border border-[#ffb3b1] px-4 py-3 font-bold text-[#ffb3b1] transition hover:bg-[#ffb3b1] hover:text-[#3b0509]" type="submit">
                                    {{ $testimonial->is_visible ? 'Sembunyikan' : 'Approve' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Hapus rating ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-full border border-white/15 px-4 py-3 font-bold text-white transition hover:bg-white/10" type="submit">Hapus</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="border border-white/10 bg-[#191919] p-8 text-[#c9c1bd] lg:col-span-2">
                        Belum ada rating dari audiens.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="mb-10 border border-white/10 bg-[#151515] p-6">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow">Karakter</p>
                    <h1 class="font-display text-3xl font-bold">Kelola Profile Halaman Depan</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#bdb4b0]">Tambahkan karakter baru selain Beni lengkap dengan foto dan deskripsi singkat.</p>
                </div>
                <p class="text-sm font-semibold text-[#bdb4b0]">{{ $profiles->count() }} profile</p>
            </div>

            <form class="mb-6 grid gap-4 border border-white/10 bg-[#191919] p-5 lg:grid-cols-[1fr_1fr_140px_auto]" method="POST" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="placement" value="profile">
                <div>
                    <label class="admin-label" for="profile-title">Nama Karakter</label>
                    <input class="admin-input" id="profile-title" name="title" type="text" value="{{ old('title') }}" placeholder="Nama profile" required>
                </div>
                <div>
                    <label class="admin-label" for="profile-category">Label</label>
                    <input class="admin-input" id="profile-category" name="category" type="text" value="{{ old('category', 'Profile') }}" placeholder="Profile" required>
                </div>
                <div>
                    <label class="admin-label" for="profile-sort">Urutan</label>
                    <input class="admin-input" id="profile-sort" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}">
                </div>
                <div>
                    <label class="admin-label" for="profile-image">Foto</label>
                    <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-4 file:py-2 file:font-bold file:text-[#3b0509]" id="profile-image" name="image" type="file" accept="image/*" data-max-upload-bytes="3670016" data-crop-upload required>
                </div>
                <div class="lg:col-span-4">
                    <label class="admin-label" for="profile-description">Deskripsi</label>
                    <textarea class="admin-input min-h-28" id="profile-description" name="description" required>{{ old('description') }}</textarea>
                </div>
                <input type="hidden" name="is_visible" value="1">
                <button class="bg-[#ffb3b1] px-6 py-4 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3] lg:col-span-4" type="submit">Tambah Profile</button>
            </form>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($profiles as $profile)
                    <article class="border border-white/10 bg-[#191919]">
                        <img class="h-64 w-full object-cover object-top" src="{{ $profile->image_url }}" alt="{{ $profile->title }}">
                        <form class="space-y-4 p-5" method="POST" action="{{ route('admin.portfolio.update', $profile) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="placement" value="profile">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="admin-label" for="profile-title-{{ $profile->id }}">Nama Karakter</label>
                                    <input class="admin-input" id="profile-title-{{ $profile->id }}" name="title" type="text" value="{{ old('title', $profile->title) }}" required>
                                </div>
                                <div>
                                    <label class="admin-label" for="profile-category-{{ $profile->id }}">Label</label>
                                    <input class="admin-input" id="profile-category-{{ $profile->id }}" name="category" type="text" value="{{ old('category', $profile->category) }}" required>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label" for="profile-description-{{ $profile->id }}">Deskripsi</label>
                                <textarea class="admin-input min-h-28" id="profile-description-{{ $profile->id }}" name="description" required>{{ old('description', $profile->description) }}</textarea>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-[1fr_120px]">
                                <div>
                                    <label class="admin-label" for="profile-image-{{ $profile->id }}">Ganti Foto</label>
                                    <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-3 file:py-2 file:font-bold file:text-[#3b0509]" id="profile-image-{{ $profile->id }}" name="image" type="file" accept="image/*" data-max-upload-bytes="3670016" data-crop-upload>
                                </div>
                                <div>
                                    <label class="admin-label" for="profile-sort-{{ $profile->id }}">Urutan</label>
                                    <input class="admin-input" id="profile-sort-{{ $profile->id }}" name="sort_order" type="number" min="0" value="{{ old('sort_order', $profile->sort_order) }}">
                                </div>
                            </div>
                            <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                                <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="is_visible" value="1" @checked($profile->is_visible)>
                                Tampilkan di halaman depan
                            </label>
                            <button class="w-full border border-[#ffb3b1] px-4 py-3 font-bold text-[#ffb3b1] transition hover:bg-[#ffb3b1] hover:text-[#3b0509]" type="submit">Update Profile</button>
                        </form>
                        <form class="px-5 pb-5" method="POST" action="{{ route('admin.portfolio.destroy', $profile) }}" onsubmit="return confirm('Hapus profile ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="w-full border border-white/15 px-4 py-3 font-bold text-white transition hover:bg-white/10" type="submit">Hapus Profile</button>
                        </form>
                    </article>
                @empty
                    <div class="border border-white/10 bg-[#191919] p-8 text-[#c9c1bd] md:col-span-2 lg:col-span-3">
                        Belum ada profile tambahan. Beni tetap tampil sebagai profile default.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="grid gap-8 lg:grid-cols-[360px_1fr] lg:items-start">
            <form class="border border-white/10 bg-[#191919] p-6 lg:sticky lg:top-6" method="POST" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="placement" value="portfolio">
                <p class="eyebrow">Tambah Media</p>
                <h1 class="font-display text-3xl font-bold">Portfolio Baru</h1>

                <div class="mt-7 space-y-5">
                    <div>
                        <label class="admin-label" for="title">Judul</label>
                        <input class="admin-input" id="title" name="title" type="text" value="{{ old('title') }}" placeholder="Wedding Story" required>
                    </div>
                    <div>
                        <label class="admin-label" for="category">Kategori</label>
                        <input class="admin-input" id="category" name="category" type="text" value="{{ old('category') }}" list="category-options" placeholder="Wedding" required>
                    </div>
                    <div>
                        <label class="admin-label" for="image">Foto / Video</label>
                        <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-4 file:py-2 file:font-bold file:text-[#3b0509]" id="image" name="image" type="file" accept="image/*,video/mp4,video/quicktime,video/webm,video/x-msvideo" data-max-upload-bytes="3670016" data-crop-upload required>
                        <p class="mt-2 text-xs font-semibold text-[#bdb4b0]">Foto otomatis dikompres sebelum upload. Video besar perlu dipasang sebagai file static.</p>
                    </div>
                    <div>
                        <label class="admin-label" for="poster">Thumbnail Video</label>
                        <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-4 file:py-2 file:font-bold file:text-[#3b0509]" id="poster" name="poster" type="file" accept="image/*" data-max-upload-bytes="3670016" data-crop-upload>
                        <p class="mt-2 text-xs font-semibold text-[#bdb4b0]">Dipakai sebagai cover kalau media yang diupload adalah video.</p>
                    </div>
                    <div>
                        <label class="admin-label" for="sort_order">Urutan</label>
                        <input class="admin-input" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}">
                    </div>
                    <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                        <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="is_visible" value="1" checked>
                        Tampilkan di halaman depan
                    </label>
                    <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                        <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="show_in_hero" value="1" @checked(old('show_in_hero'))>
                        Masukkan foto ini ke hero slider
                    </label>
                    <button class="w-full bg-[#ffb3b1] px-6 py-4 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3]" type="submit">Simpan Media</button>
                </div>
            </form>

            <section>
                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="eyebrow">Daftar Media</p>
                        <h2 class="font-display text-3xl font-bold">Kelola Portfolio</h2>
                    </div>
                    <p class="text-sm font-semibold text-[#bdb4b0]">{{ $photos->count() }} media</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    @forelse ($photos as $photo)
                        <article class="border border-white/10 bg-[#191919]">
                            @if ($photo->media_type === 'video')
                                <video class="h-64 w-full object-cover" src="{{ $photo->image_url }}" poster="{{ $photo->poster_url }}" controls preload="metadata"></video>
                            @else
                                <img class="h-64 w-full object-cover" src="{{ $photo->image_url }}" alt="{{ $photo->title }}">
                            @endif
                            <form class="space-y-4 p-5" method="POST" action="{{ route('admin.portfolio.update', $photo) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="placement" value="portfolio">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="admin-label" for="title-{{ $photo->id }}">Judul</label>
                                        <input class="admin-input" id="title-{{ $photo->id }}" name="title" type="text" value="{{ old('title', $photo->title) }}" required>
                                    </div>
                                    <div>
                                        <label class="admin-label" for="category-{{ $photo->id }}">Kategori</label>
                                        <input class="admin-input" id="category-{{ $photo->id }}" name="category" type="text" list="category-options" value="{{ old('category', $photo->category) }}" required>
                                    </div>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-[1fr_120px]">
                                    <div>
                                        <label class="admin-label" for="image-{{ $photo->id }}">Ganti Foto / Video</label>
                                        <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-3 file:py-2 file:font-bold file:text-[#3b0509]" id="image-{{ $photo->id }}" name="image" type="file" accept="image/*,video/mp4,video/quicktime,video/webm,video/x-msvideo" data-max-upload-bytes="3670016" data-crop-upload>
                                    </div>
                                    <div>
                                        <label class="admin-label" for="sort-{{ $photo->id }}">Urutan</label>
                                        <input class="admin-input" id="sort-{{ $photo->id }}" name="sort_order" type="number" min="0" value="{{ old('sort_order', $photo->sort_order) }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="admin-label" for="poster-{{ $photo->id }}">Ganti Thumbnail Video</label>
                                    <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-3 file:py-2 file:font-bold file:text-[#3b0509]" id="poster-{{ $photo->id }}" name="poster" type="file" accept="image/*" data-max-upload-bytes="3670016" data-crop-upload>
                                    <p class="mt-2 text-xs font-semibold text-[#bdb4b0]">Thumbnail hanya dipakai kalau media ini video.</p>
                                </div>
                                <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                                    <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="is_visible" value="1" @checked($photo->is_visible)>
                                    Tampilkan
                                </label>
                                @if ($photo->media_type === 'image')
                                    <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                                        <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="show_in_hero" value="1" @checked($photo->show_in_hero)>
                                        Masukkan ke hero slider
                                    </label>
                                @endif
                                <div class="flex flex-col gap-3 sm:flex-row">
                                    <button class="flex-1 border border-[#ffb3b1] px-4 py-3 font-bold text-[#ffb3b1] transition hover:bg-[#ffb3b1] hover:text-[#3b0509]" type="submit">Update</button>
                                </div>
                            </form>
                            <form class="px-5 pb-5" method="POST" action="{{ route('admin.portfolio.destroy', $photo) }}" onsubmit="return confirm('Hapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-full border border-white/15 px-4 py-3 font-bold text-white transition hover:bg-white/10" type="submit">Hapus</button>
                            </form>
                        </article>
                    @empty
                        <div class="border border-white/10 bg-[#191919] p-8 text-[#c9c1bd] md:col-span-2">
                            Belum ada media. Tambahkan foto atau video pertama dari form di samping.
                        </div>
                    @endforelse
                </div>
            </section>
        </section>

        <datalist id="category-options">
            @foreach ($categories as $category)
                <option value="{{ $category }}"></option>
            @endforeach
        </datalist>

        <div class="crop-modal hidden" data-crop-modal aria-hidden="true">
            <div class="crop-modal-panel">
                <div>
                    <p class="eyebrow">Crop Foto</p>
                    <h2 class="font-display text-3xl font-bold">Geser dan Crop Sebelum Upload</h2>
                </div>
                <div class="crop-stage" data-crop-stage>
                    <canvas data-crop-canvas></canvas>
                    <div class="crop-frame" aria-hidden="true"></div>
                </div>
                <div class="crop-controls">
                    <button type="button" data-crop-zoom-out>-</button>
                    <input type="range" min="1" max="3" step="0.05" value="1" data-crop-zoom aria-label="Zoom crop">
                    <button type="button" data-crop-zoom-in>+</button>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <button class="border border-white/15 px-4 py-3 font-bold text-white transition hover:bg-white/10" type="button" data-crop-cancel>Batal</button>
                    <button class="bg-[#ffb3b1] px-4 py-3 font-bold text-[#3b0509] transition hover:bg-[#ffd6d3]" type="button" data-crop-apply>Pakai Foto Ini</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

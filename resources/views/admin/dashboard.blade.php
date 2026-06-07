<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - R&D Photography</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#111111] text-[#f3f0ed] antialiased">
    <header class="border-b border-white/10 bg-[#151515]">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-5 py-5 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10">
            <div>
                <a href="{{ url('/') }}" class="brand-mark" aria-label="R&D Photography">
                    <img src="{{ asset('images/rd-potrait-logo.png') }}" alt="R&D Photography">
                    <span>R&D Photography</span>
                </a>
                <p class="mt-1 text-sm text-[#bdb4b0]">Dashboard foto welcome dan portfolio halaman depan</p>
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
                    <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-4 file:py-2 file:font-bold file:text-[#3b0509]" id="hero-image" name="image" type="file" accept="image/*" required>
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
                                    <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-3 file:py-2 file:font-bold file:text-[#3b0509]" id="hero-image-{{ $photo->id }}" name="image" type="file" accept="image/*">
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
                        <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-4 file:py-2 file:font-bold file:text-[#3b0509]" id="image" name="image" type="file" accept="image/*,video/mp4,video/quicktime,video/webm,video/x-msvideo" required>
                        <p class="mt-2 text-xs font-semibold text-[#bdb4b0]">Foto otomatis mengikuti frame. Video maksimal 50MB.</p>
                    </div>
                    <div>
                        <label class="admin-label" for="sort_order">Urutan</label>
                        <input class="admin-input" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}">
                    </div>
                    <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                        <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="is_visible" value="1" checked>
                        Tampilkan di halaman depan
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
                                <video class="h-64 w-full object-cover" src="{{ $photo->image_url }}" controls preload="metadata"></video>
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
                                        <input class="admin-input file:border-0 file:bg-[#ffb3b1] file:px-3 file:py-2 file:font-bold file:text-[#3b0509]" id="image-{{ $photo->id }}" name="image" type="file" accept="image/*,video/mp4,video/quicktime,video/webm,video/x-msvideo">
                                    </div>
                                    <div>
                                        <label class="admin-label" for="sort-{{ $photo->id }}">Urutan</label>
                                        <input class="admin-input" id="sort-{{ $photo->id }}" name="sort_order" type="number" min="0" value="{{ old('sort_order', $photo->sort_order) }}">
                                    </div>
                                </div>
                                <label class="flex items-center gap-3 text-sm font-semibold text-[#d9d3cf]">
                                    <input class="h-4 w-4 accent-[#ffb3b1]" type="checkbox" name="is_visible" value="1" @checked($photo->is_visible)>
                                    Tampilkan
                                </label>
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
    </main>
</body>
</html>

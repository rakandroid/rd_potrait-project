<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\PortfolioPhotoController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TestimonialController;
use App\Models\AudienceTestimonial;
use App\Models\PortfolioPhoto;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }

    $heroPhotos = PortfolioPhoto::query()
        ->where('placement', 'hero')
        ->where('is_visible', true)
        ->orderBy('sort_order')
        ->latest()
        ->get();

    if ($heroPhotos->isEmpty()) {
        $heroPhotos = collect(range(1, 6))->map(fn (int $index) => (object) [
            'title' => "RD Potrait {$index}",
            'image_url' => asset('images/hero/model-0'.$index.'.jpeg'),
        ]);
    }

    $portfolioMedia = PortfolioPhoto::query()
        ->where('placement', 'portfolio')
        ->where('is_visible', true)
        ->orderBy('sort_order')
        ->latest()
        ->get();

    $heroPortfolioPhotos = PortfolioPhoto::query()
        ->where('placement', 'portfolio')
        ->where('show_in_hero', true)
        ->where('is_visible', true)
        ->orderBy('sort_order')
        ->latest()
        ->get();

    $heroPhotos = $heroPhotos->concat($heroPortfolioPhotos);

    if ($portfolioMedia->isEmpty()) {
        $portfolioMedia = collect([
            ['title' => 'Portrait Session', 'category' => 'Portrait', 'image' => 'beni-profile-camera.jpeg'],
            ['title' => 'Character Study', 'category' => 'Portrait', 'image' => 'beni-character.jpeg'],
            ['title' => 'Video Highlight', 'category' => 'Video', 'video' => 'jzjrPp4LqmWRtjA3wKHe1Fd3Kpc2T12drRK0nmVI.mp4'],
        ])->map(fn (array $photo) => (object) [
            'title' => $photo['title'],
            'category' => $photo['category'],
            'media_type' => isset($photo['video']) ? 'video' : 'image',
            'image_url' => isset($photo['video'])
                ? asset('videos/'.$photo['video'])
                : asset('images/portfolio/'.$photo['image']),
        ]);
    }

    $audienceTestimonials = AudienceTestimonial::query()
        ->where('is_visible', true)
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', [
        'heroPhotos' => $heroPhotos->take(6),
        'portfolioMedia' => $portfolioMedia,
        'portfolioVideos' => $portfolioMedia->where('media_type', 'video'),
        'portfolioPhotos' => $portfolioMedia->where('media_type', 'image'),
        'audienceTestimonials' => $audienceTestimonials,
        'settings' => SiteSetting::current(),
    ]);
});

Route::get('/profile-fotografer', function () {
    $defaultProfiles = collect([
        (object) [
            'title' => 'Raden Beni Darmansyah',
            'category' => 'Fotografer',
            'description' => 'Ada banyak cerita di dunia ini yang terlalu sunyi untuk didengar, namun terlalu indah untuk dilewatkan. Saya memilih menjadi perantara bagi cerita-cerita itu; mendokumentasikan senyapnya perjuangan, ketulusan cinta, dan air mata yang berbicara tanpa suara.',
            'image_url' => file_exists(public_path('images/beni-character.png'))
                ? asset('images/beni-character.png')
                : asset('images/beni-character.jpeg'),
        ],
    ]);

    $profiles = PortfolioPhoto::query()
        ->where('placement', 'profile')
        ->where('is_visible', true)
        ->orderBy('sort_order')
        ->latest()
        ->get();

    return view('profile-fotografer', [
        'profiles' => $defaultProfiles->concat($profiles),
        'settings' => SiteSetting::current(),
    ]);
})->name('profile');

Route::post('/booking', BookingController::class)->name('booking.store');
Route::post('/testimoni', TestimonialController::class)->name('testimonials.store');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'store'])->name('admin.login.store');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::put('/password', [AuthController::class, 'updatePassword'])->name('password.update');
    Route::get('/', [PortfolioPhotoController::class, 'index'])->name('dashboard');
    Route::post('/portfolio', [PortfolioPhotoController::class, 'store'])->name('portfolio.store');
    Route::put('/portfolio/{portfolioPhoto}', [PortfolioPhotoController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolio/{portfolioPhoto}', [PortfolioPhotoController::class, 'destroy'])->name('portfolio.destroy');
    Route::put('/settings', SiteSettingController::class)->name('settings.update');
    Route::put('/bookings/{booking}', [BookingAdminController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingAdminController::class, 'destroy'])->name('bookings.destroy');
    Route::put('/testimonials/{testimonial}', [AdminTestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');
});

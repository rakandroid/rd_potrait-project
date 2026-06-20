<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudienceTestimonial;
use App\Models\Booking;
use App\Models\PortfolioPhoto;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PortfolioPhotoController extends Controller
{
    private const VERCEL_UPLOAD_MAX_KILOBYTES = 2048;

    public function index(): View
    {
        return view('admin.dashboard', [
            'heroPhotos' => PortfolioPhoto::query()
                ->where('placement', 'hero')
                ->orderBy('sort_order')
                ->latest()
                ->get(),
            'photos' => PortfolioPhoto::query()
                ->where('placement', 'portfolio')
                ->orderBy('sort_order')
                ->latest()
                ->get(),
            'profiles' => PortfolioPhoto::query()
                ->where('placement', 'profile')
                ->orderBy('sort_order')
                ->latest()
                ->get(),
            'bookings' => Booking::query()
                ->whereNotNull('event_date')
                ->orderBy('event_date')
                ->latest()
                ->get(),
            'settings' => SiteSetting::current(),
            'testimonials' => AudienceTestimonial::query()
                ->latest()
                ->get(),
            'categories' => ['Wedding', 'Pre-Wedding', 'Event', 'Portrait', 'Details'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'placement' => ['required', 'in:hero,portfolio,profile'],
            'description' => ['nullable', 'string', 'max:1200'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,mov,webm,avi', 'max:'.self::VERCEL_UPLOAD_MAX_KILOBYTES],
            'poster' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:'.self::VERCEL_UPLOAD_MAX_KILOBYTES],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
            'show_in_hero' => ['nullable', 'boolean'],
        ]);
        unset($data['poster']);

        $uploadedFile = $request->file('image');
        $data['media_type'] = str_starts_with($uploadedFile->getMimeType() ?? '', 'video/') ? 'video' : 'image';

        if ($data['placement'] === 'hero' && $data['media_type'] !== 'image') {
            return back()
                ->withErrors(['image' => 'Slide welcome hanya bisa memakai file foto.'])
                ->withInput();
        }

        if ($data['placement'] === 'profile' && $data['media_type'] !== 'image') {
            return back()
                ->withErrors(['image' => 'Karakter/profile hanya bisa memakai file foto.'])
                ->withInput();
        }

        $data['image_path'] = $this->storeUploadedMedia($uploadedFile, $data['placement'], $data['media_type']);
        $data['poster_path'] = $this->storeVideoPoster($request, $data['media_type']);
        $data['is_visible'] = $request->boolean('is_visible', true);
        $data['show_in_hero'] = $data['placement'] === 'portfolio' && $data['media_type'] === 'image'
            ? $request->boolean('show_in_hero')
            : false;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        PortfolioPhoto::create($data);

        return back()->with('status', 'Foto portfolio berhasil ditambahkan.');
    }

    public function update(Request $request, PortfolioPhoto $portfolioPhoto): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'placement' => ['required', 'in:hero,portfolio,profile'],
            'description' => ['nullable', 'string', 'max:1200'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,mov,webm,avi', 'max:'.self::VERCEL_UPLOAD_MAX_KILOBYTES],
            'poster' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:'.self::VERCEL_UPLOAD_MAX_KILOBYTES],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
            'show_in_hero' => ['nullable', 'boolean'],
        ]);
        unset($data['poster']);

        $mediaType = $portfolioPhoto->media_type;

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $data['media_type'] = str_starts_with($uploadedFile->getMimeType() ?? '', 'video/') ? 'video' : 'image';
            $mediaType = $data['media_type'];

            if ($data['placement'] === 'hero' && $data['media_type'] !== 'image') {
                return back()
                    ->withErrors(['image' => 'Slide welcome hanya bisa memakai file foto.'])
                    ->withInput();
            }

            if ($data['placement'] === 'profile' && $data['media_type'] !== 'image') {
                return back()
                    ->withErrors(['image' => 'Karakter/profile hanya bisa memakai file foto.'])
                    ->withInput();
            }

            $this->deleteUploadedMedia($portfolioPhoto->image_path);

            $data['image_path'] = $this->storeUploadedMedia($uploadedFile, $data['placement'], $data['media_type']);
        }

        if ($mediaType === 'video' && $request->hasFile('poster')) {
            $this->deleteUploadedMedia($portfolioPhoto->poster_path);
            $data['poster_path'] = $this->storeVideoPoster($request, $mediaType);
        }

        if ($mediaType !== 'video') {
            $this->deleteUploadedMedia($portfolioPhoto->poster_path);
            $data['poster_path'] = null;
        }

        $data['is_visible'] = $request->boolean('is_visible');
        $data['show_in_hero'] = $data['placement'] === 'portfolio' && $mediaType === 'image'
            ? $request->boolean('show_in_hero')
            : false;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $portfolioPhoto->update($data);

        return back()->with('status', 'Foto portfolio berhasil diperbarui.');
    }

    public function destroy(PortfolioPhoto $portfolioPhoto): RedirectResponse
    {
        $this->deleteUploadedMedia($portfolioPhoto->image_path);
        $this->deleteUploadedMedia($portfolioPhoto->poster_path);

        $portfolioPhoto->delete();

        return back()->with('status', 'Foto portfolio berhasil dihapus.');
    }

    private function storeUploadedMedia($uploadedFile, string $placement, string $mediaType): string
    {
        if ($mediaType === 'image') {
            $mimeType = $uploadedFile->getMimeType() ?: 'image/jpeg';
            $contents = file_get_contents($uploadedFile->getRealPath());

            return 'data:'.$mimeType.';base64,'.base64_encode($contents);
        }

        $extension = strtolower($uploadedFile->getClientOriginalExtension() ?: $uploadedFile->guessExtension() ?: 'bin');
        $filename = Str::uuid().'.'.$extension;

        Storage::disk('uploads')->putFileAs($placement, $uploadedFile, $filename);

        return "uploads/{$placement}/{$filename}";
    }

    private function storeVideoPoster(Request $request, string $mediaType): ?string
    {
        if ($mediaType !== 'video' || ! $request->hasFile('poster')) {
            return null;
        }

        return $this->storeUploadedMedia($request->file('poster'), 'poster', 'image');
    }

    private function deleteUploadedMedia(?string $imagePath): void
    {
        if (! $imagePath || str_starts_with($imagePath, 'http') || str_starts_with($imagePath, 'data:')) {
            return;
        }

        $path = ltrim($imagePath, '/');

        if (str_starts_with($path, 'uploads/')) {
            Storage::disk('uploads')->delete(substr($path, strlen('uploads/')));

            return;
        }

        Storage::disk('public')->delete($path);
    }
}

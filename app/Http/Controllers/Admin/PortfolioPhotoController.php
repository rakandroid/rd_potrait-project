<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudienceTestimonial;
use App\Models\PortfolioPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortfolioPhotoController extends Controller
{
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
            'placement' => ['required', 'in:hero,portfolio'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,mov,webm,avi', 'max:51200'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $uploadedFile = $request->file('image');
        $data['media_type'] = str_starts_with($uploadedFile->getMimeType() ?? '', 'video/') ? 'video' : 'image';

        if ($data['placement'] === 'hero' && $data['media_type'] !== 'image') {
            return back()
                ->withErrors(['image' => 'Slide welcome hanya bisa memakai file foto.'])
                ->withInput();
        }

        $data['image_path'] = $uploadedFile->store($data['placement'], 'public');
        $data['is_visible'] = $request->boolean('is_visible', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        PortfolioPhoto::create($data);

        return back()->with('status', 'Foto portfolio berhasil ditambahkan.');
    }

    public function update(Request $request, PortfolioPhoto $portfolioPhoto): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'placement' => ['required', 'in:hero,portfolio'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,mov,webm,avi', 'max:51200'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $data['media_type'] = str_starts_with($uploadedFile->getMimeType() ?? '', 'video/') ? 'video' : 'image';

            if ($data['placement'] === 'hero' && $data['media_type'] !== 'image') {
                return back()
                    ->withErrors(['image' => 'Slide welcome hanya bisa memakai file foto.'])
                    ->withInput();
            }

            if (! str_starts_with($portfolioPhoto->image_path, 'http')) {
                Storage::disk('public')->delete($portfolioPhoto->image_path);
            }

            $data['image_path'] = $uploadedFile->store($data['placement'], 'public');
        }

        $data['is_visible'] = $request->boolean('is_visible');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $portfolioPhoto->update($data);

        return back()->with('status', 'Foto portfolio berhasil diperbarui.');
    }

    public function destroy(PortfolioPhoto $portfolioPhoto): RedirectResponse
    {
        if (! str_starts_with($portfolioPhoto->image_path, 'http')) {
            Storage::disk('public')->delete($portfolioPhoto->image_path);
        }

        $portfolioPhoto->delete();

        return back()->with('status', 'Foto portfolio berhasil dihapus.');
    }
}

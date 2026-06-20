<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteSettingController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'backsound' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:8192'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
        ]);

        $settings = SiteSetting::current();

        if ($request->hasFile('backsound')) {
            $this->deleteUpload($settings->backsound_path);

            $file = $request->file('backsound');
            $filename = Str::uuid().'.'.strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'mp3');

            Storage::disk('uploads')->putFileAs('audio', $file, $filename);
            $data['backsound_path'] = "uploads/audio/{$filename}";
        }

        unset($data['backsound']);

        $settings->update($data);

        return back()->with('status', 'Pengaturan website berhasil diperbarui.');
    }

    private function deleteUpload(?string $path): void
    {
        if (! $path || ! str_starts_with($path, 'uploads/')) {
            return;
        }

        Storage::disk('uploads')->delete(substr($path, strlen('uploads/')));
    }
}

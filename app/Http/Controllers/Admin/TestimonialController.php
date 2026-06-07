<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudienceTestimonial;
use Illuminate\Http\RedirectResponse;

class TestimonialController extends Controller
{
    public function update(AudienceTestimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'is_visible' => ! $testimonial->is_visible,
        ]);

        return back()->with('status', $testimonial->is_visible
            ? 'Rating berhasil di-approve dan tampil di halaman depan.'
            : 'Rating disembunyikan dari halaman depan.');
    }

    public function destroy(AudienceTestimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return back()->with('status', 'Rating berhasil dihapus.');
    }
}

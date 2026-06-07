<?php

namespace App\Http\Controllers;

use App\Models\AudienceTestimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'testimonial_name' => ['required', 'string', 'max:120'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'testimonial_message' => ['required', 'string', 'min:8', 'max:500'],
        ]);

        AudienceTestimonial::create([
            'name' => $data['testimonial_name'],
            'rating' => $data['rating'],
            'message' => $data['testimonial_message'],
            'is_visible' => false,
        ]);

        return redirect('/#testimoni')
            ->with('testimonial_success', 'Terima kasih, testimoni kamu sudah masuk dan menunggu approve admin.');
    }
}

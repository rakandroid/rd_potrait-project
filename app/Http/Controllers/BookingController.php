<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'service' => ['required', 'string', 'max:80'],
            'event_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        if (filled($data['event_date'] ?? null)) {
            $isBooked = Booking::query()
                ->whereDate('event_date', $data['event_date'])
                ->where('status', 'booked')
                ->exists();

            if ($isBooked) {
                return back()
                    ->withErrors(['event_date' => 'Tanggal ini sudah berstatus booked. Silakan pilih tanggal lain atau konsultasi via WhatsApp.'])
                    ->withInput();
            }
        }

        $data['status'] = 'pending';
        Booking::create($data);
        $eventDate = filled($data['event_date'] ?? null)
            ? Carbon::parse($data['event_date'])->locale('id')->translatedFormat('d F Y')
            : '-';

        $lines = [
            'Halo RD Potrait, saya ingin booking.',
            'Nama: '.$data['name'],
            'No HP: '.$data['phone'],
            'Layanan: '.$data['service'],
            'Tanggal acara: '.$eventDate,
            'Lokasi: '.($data['location'] ?? '-'),
            'Catatan: '.($data['message'] ?? '-'),
        ];

        return redirect()->away('https://wa.me/628551250670?text='.rawurlencode(implode("\n", $lines)));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:pending,booked,done,cancelled'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $booking->update($data);

        return back()->with('status', 'Label jadwal berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return back()->with('status', 'Jadwal booking berhasil dihapus.');
    }
}

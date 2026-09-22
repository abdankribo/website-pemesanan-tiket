<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string|different:origin',
            'departureDate' => 'required|date',
            'roundTrip' => 'sometimes|in:1',
            'returnDate' => 'nullable|date',
            'serviceType' => 'nullable|string',
        ]);

        if (strtotime($data['departureDate']) < strtotime(now()->toDateString())) {
            return redirect()->back()->withInput()->withErrors([
                'departureDate' => 'Tanggal keberangkatan tidak boleh sebelum hari ini.',
            ]);
        }

        // If roundTrip is set, ensure returnDate exists and is after or equal departure
        if ($request->has('roundTrip')) {
            if (empty($data['returnDate'])) {
                return redirect()->back()->withInput()->withErrors(['returnDate' => 'Return date is required for round-trip']);
            }
            if (strtotime($data['returnDate']) < strtotime($data['departureDate'])) {
                return redirect()->back()->withInput()->withErrors(['returnDate' => 'Return date must be after departure date']);
            }
        }

        // Build query params and redirect to booking page
        $params = [
            'origin' => $data['origin'],
            'destination' => $data['destination'],
            'departureDate' => $data['departureDate'],
        ];

        if ($request->has('roundTrip')) {
            $params['roundTrip'] = 1;
            $params['returnDate'] = $data['returnDate'];
        }

        if (!empty($data['serviceType'])) {
            $params['serviceType'] = $data['serviceType'];
        }

        $query = http_build_query($params);
        return redirect(url('/booking') . '?' . $query);
    }
}

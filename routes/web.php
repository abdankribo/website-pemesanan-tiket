<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->intended(route('booking'));
    }

    return view('login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    $request->session()->regenerate();

    return redirect()->intended(route('booking'));
})->name('login.submit');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => ['required', 'email']]);
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'Link reset password sudah dikirim ke email Anda.')
        : back()->withErrors(['email' => 'Email tersebut belum terdaftar.']);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('reset-password', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $status = Password::reset(
        $data,
        function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login kembali.')
        : back()->withErrors(['email' => 'Link reset tidak valid atau sudah kedaluwarsa.']);
})->middleware('guest')->name('password.update');

Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->intended(route('booking'));
    }

    return view('register');
})->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->intended(route('booking'));
})->name('register.submit');

Route::get('/auth/google', function () {
    if (!config('services.google.client_id') || !config('services.google.client_secret')) {
        return redirect()->route('login')->withErrors([
            'google' => 'Login Google belum dikonfigurasi. Isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET terlebih dahulu.',
        ]);
    }

    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();
    $googleEmail = strtolower(trim((string) $googleUser->getEmail()));

    if ($googleEmail === '') {
        return redirect()->route('login')->withErrors([
            'google' => 'Email Google tidak tersedia. Silakan gunakan login email biasa.',
        ]);
    }

    $user = User::where('google_id', $googleUser->getId())
        ->orWhereRaw('LOWER(email) = ?', [$googleEmail])
        ->first();

    if (!$user) {
        $user = User::create([
            'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
            'email' => $googleEmail,
            'password' => Hash::make(Str::random(40)),
            'google_id' => $googleUser->getId(),
        ]);
    } else {
        $user->forceFill([
            'google_id' => $googleUser->getId(),
            'email' => $googleEmail,
            'name' => $user->name ?: ($googleUser->getName() ?: 'Google User'),
        ])->save();
    }

    Auth::login($user, true);
    request()->session()->regenerate();

    return redirect()->intended(route('booking'));
})->name('google.callback');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('landing');
})->name('logout');

Route::post('/session/heartbeat', function (\Illuminate\Http\Request $request) {
    return response()->noContent();
})->middleware('auth')->name('session.heartbeat');

Route::get('/account', function () {
    return view('welcome');
})->middleware('auth')->name('account');

Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
    $profile = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'phone' => ['required', 'regex:/^08[0-9]{8,11}$/'],
        'nik' => ['required', 'digits:16'],
        'email' => ['required', 'email', 'max:150'],
        'birth_date' => ['required', 'date', 'before:today'],
    ]);

    $request->user()->update([
        'name' => $profile['name'],
        'email' => $profile['email'],
        'phone' => $profile['phone'],
        'nik' => $profile['nik'],
        'birth_date' => $profile['birth_date'],
    ]);
    session(['profile' => $profile]);

    return redirect()->route('account')->with('profile_saved', 'Profil berhasil diperbarui.');
})->middleware('auth')->name('profile.update');

Route::post('/profile/clear', function (\Illuminate\Http\Request $request) {
    DB::table('tickets')->where('user_id', $request->user()->id)->delete();
    $request->user()->update([
        'phone' => null,
        'nik' => null,
        'birth_date' => null,
    ]);
    $request->session()->forget('profile');
    $request->session()->forget(['booking_data', 'payment_completed', 'ticket_id', 'scanned_booking']);

    return redirect()->route('account')->with('profile_cleared', 'Data profile berhasil dikosongkan.');
})->middleware('auth')->name('profile.clear');

Route::get('/welcome', function () {
    return redirect()->route('account');
});

Route::get('/booking', function () {
    return view('booking');
})->middleware('auth')->name('booking');

Route::get('/payment', function () {
    $bookingData = request()->only([
        'origin',
        'destination',
        'departureDate',
        'serviceType',
        'vehicle',
        'vehiclePlate',
        'passengerName',
        'passengerNik',
        'passengerPhone',
    ]);
    foreach (['origin', 'destination', 'departureDate', 'passengerName', 'passengerNik', 'passengerPhone'] as $field) {
        if (empty($bookingData[$field])) {
            return redirect()->route('booking')->withErrors([
                'booking' => 'Data pemesanan belum lengkap. Silakan isi kembali sebelum melanjutkan.',
            ]);
        }
    }

    session(['booking_data' => $bookingData]);

    return view('payment');
})->middleware('auth')->name('payment');

Route::get('/ticket', function () {
    if (!session('payment_completed')) {
        return redirect()->route('payment');
    }

    return view('ticket');
})->name('ticket');

Route::get('/ticket/verify', function () {
    $ticketRecord = DB::table('tickets')->where('ticket_id', request('ticket'))->first();

    if (!$ticketRecord) {
        return view('ticket-verify', ['ticket' => []]);
    }

    $status = \Carbon\Carbon::parse($ticketRecord->departure_date)->isBefore(today())
        ? 'expired'
        : 'completed';
    DB::table('tickets')->where('id', $ticketRecord->id)->update([
        'status' => $status,
        'scanned_at' => now(),
        'updated_at' => now(),
    ]);
    $ticket = [
        'name' => $ticketRecord->passenger_name,
        'nik' => $ticketRecord->passenger_nik,
        'phone' => $ticketRecord->passenger_phone,
        'route' => ucfirst($ticketRecord->origin) . ' - ' . ucfirst($ticketRecord->destination),
        'departure_date' => \Carbon\Carbon::parse($ticketRecord->departure_date)->format('d M Y'),
        'vehicle_plate' => $ticketRecord->vehicle_plate ?: 'Tidak ada',
        'status' => $status,
    ];
    session(['scanned_booking' => $ticket]);

    return view('ticket-verify', [
        'ticket' => $ticket,
    ]);
})->name('ticket.verify');

Route::get('/payment/complete', function () {
    return redirect()->route('payment')->withErrors([
        'payment' => 'Halaman pembayaran harus dikirim melalui tombol Pay Now.',
    ]);
})->middleware('auth')->name('payment.complete.get');

Route::post('/payment/complete', function (\Illuminate\Http\Request $request) {
    if (!$request->filled('payment_method')) {
        return redirect()->back()->withErrors([
            'payment_method' => 'Metode pembayaran wajib dipilih.',
        ]);
    }

    $booking = session('booking_data', []);
    $requiredBookingFields = [
        'passengerName',
        'passengerNik',
        'passengerPhone',
        'departureDate',
    ];
    foreach ($requiredBookingFields as $field) {
        if (empty($booking[$field])) {
            return redirect()->route('booking')->withErrors([
                'booking' => 'Sesi pemesanan berakhir. Silakan isi data pemesanan kembali.',
            ]);
        }
    }

    $ticketId = (string) Str::uuid();
    DB::table('tickets')->insert([
        'user_id' => $request->user()->id,
        'ticket_id' => $ticketId,
        'passenger_name' => $booking['passengerName'],
        'passenger_nik' => $booking['passengerNik'],
        'passenger_phone' => $booking['passengerPhone'],
        'origin' => $booking['origin'] ?? 'ujung',
        'destination' => $booking['destination'] ?? 'kamal',
        'departure_date' => $booking['departureDate'],
        'vehicle' => $booking['vehicle'] ?? 'passenger',
        'vehicle_plate' => $booking['vehiclePlate'] ?? null,
        'status' => 'booked',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    session(['payment_completed' => true]);
    session(['ticket_id' => $ticketId]);

    return redirect()->route('ticket');
})->name('payment.complete');

Route::get('/my-tickets', function () {
    return view('my-tickets');
});

Route::get('/landing', function () {
    return redirect()->route('landing');
});

use App\Http\Controllers\SearchController;
Route::get('/search', [SearchController::class, 'search'])->middleware('auth')->name('search');


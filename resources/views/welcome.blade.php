@extends('layouts.app')

@section('bodyClass', 'text-on-surface')

@section('content')
@php
  $serverTicket = session('ticket_id')
    ? \Illuminate\Support\Facades\DB::table('tickets')->where('ticket_id', session('ticket_id'))->first()
    : null;
  $hasScannedBooking = $serverTicket && $serverTicket->scanned_at;
  $scannedBooking = $serverTicket ? [
    'route' => ucfirst($serverTicket->origin) . ' - ' . ucfirst($serverTicket->destination),
    'departure_date' => \Carbon\Carbon::parse($serverTicket->departure_date)->format('d M Y'),
    'name' => $serverTicket->passenger_name,
  ] : session('scanned_booking');
  $scannedDate = $serverTicket
    ? \Carbon\Carbon::parse($serverTicket->departure_date)
    : null;
  $scannedStatus = $serverTicket && $serverTicket->status === 'expired' ? 'Expired' : 'Completed';
  $accountUser = auth()->user();
  $profile = array_merge([
    'name' => $accountUser->name,
    'phone' => $accountUser->phone ?? '',
    'nik' => $accountUser->nik ?? '',
    'email' => $accountUser->email,
    'birth_date' => $accountUser->birth_date?->format('Y-m-d') ?? '',
  ], session('profile', []));
@endphp
<header
  class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl dark:bg-slate-900/80 shadow-[0_8px_24px_-2px_rgba(25,28,30,0.06)]">
  <div class="flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4 w-full max-w-7xl mx-auto">
    <div class="flex items-center gap-2 min-w-0">
      <div
        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-primary-fixed flex items-center justify-center overflow-hidden border-2 border-primary-container shrink-0">
        <img alt="User profile photo" class="w-full h-full object-cover"
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuD6sJ0NX1w98YV4ChZ2u2hOXDp1dq8bKp5-U7abNM1NDKQcTY9zAcuzPGZAqH_com5h7reaqRzQpG_Y9kZ3CjkiAQpQjWlleOxItEFPeMd07BIhtFcR5OHNzlvFZGmjhlQX9BlvCXHs9EB0wA2ef3p0nYYZN5W9uFlBaylZiTE-rFlH5ozC_hMxohb0LHnMDJng2-DpwrCWLvvKBGWOQsVugZBU6_POZC8VJJE_QqwXFdzeZSCHpNw577O1EeyTjXbA2kNnfNQaiwig" />
      </div>
      <h1 class="text-blue-900 dark:text-blue-300 font-['Inter'] tracking-tight font-bold text-sm sm:text-lg truncate">
        Your Profile
      </h1>
    </div>
    <div class="flex items-center gap-1 sm:gap-3 shrink-0">
      <a href="{{ url('/landing') }}" title="Explore Dashboard" aria-label="Explore Dashboard" class="inline-flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2 rounded-full bg-slate-900 text-white text-sm font-bold hover:bg-slate-700 transition-colors">
        <span class="material-symbols-outlined text-lg sm:hidden">explore</span>
        <span class="hidden sm:inline">Explore Dashboard</span>
      </a>
      <a href="{{ url('/my-tickets') }}" title="My Tickets" aria-label="My Tickets" class="inline-flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2 rounded-full bg-secondary text-white text-sm font-bold hover:bg-secondary-container transition-colors">
        <span class="material-symbols-outlined text-lg sm:hidden">confirmation_number</span>
        <span class="hidden sm:inline">My Tickets</span>
      </a>
      <a href="{{ url('/booking') }}?origin=ujung&destination=kamal&departureDate={{ now()->toDateString() }}&serviceType=passenger&vehicle=passenger" title="Book Ticket" aria-label="Book Ticket" class="inline-flex items-center justify-center w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2 rounded-full bg-primary text-white text-sm font-bold hover:bg-primary-container transition-colors">
        <span class="material-symbols-outlined text-lg sm:hidden">add</span>
        <span class="hidden sm:inline">Book Ticket</span>
      </a>
      <button aria-label="Notifications" title="Notifications"
        class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors active:scale-90">
        <span class="material-symbols-outlined text-blue-900 dark:text-blue-300"
          data-icon="notifications">notifications</span>
      </button>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" aria-label="Logout" title="Logout"
          class="inline-flex h-9 w-9 items-center justify-center rounded-full text-primary transition-colors hover:bg-surface-container-high sm:h-auto sm:w-auto sm:gap-2 sm:rounded-full sm:bg-surface-container-low sm:px-4 sm:py-2 sm:text-sm sm:font-bold">
          <span class="material-symbols-outlined text-lg">logout</span>
          <span class="hidden sm:inline">Logout</span>
        </button>
      </form>
    </div>
  </div>
</header>
<main class="pt-20 sm:pt-24 pb-32 px-4 sm:px-6 max-w-md mx-auto">
  <section class="mb-8 sm:mb-10">
    <div class="flex flex-col items-center text-center">
      <h2 id="profileDisplayName" class="text-2xl sm:text-3xl font-extrabold tracking-tighter text-blue-900 mb-1">
        {{ $profile['name'] }}
      </h2>
      <p id="profileDisplayEmail" class="text-on-surface-variant text-sm mb-5 sm:mb-6 break-all">
        {{ $profile['email'] }}
      </p>
      <button id="editProfileBtn" type="button"
        class="bg-surface-container-high px-5 sm:px-6 py-2.5 rounded-full text-blue-900 font-bold text-sm flex items-center gap-2 hover:bg-slate-200 transition-colors active:scale-95">
        <span class="material-symbols-outlined text-[18px]" data-icon="edit">edit</span>
        Edit Profile
      </button>
      @if (session('profile_cleared'))
        <p class="mt-3 text-xs font-semibold text-[#166534]">{{ session('profile_cleared') }}</p>
      @endif
      <form action="{{ route('profile.clear') }}" method="POST" class="mt-3" onsubmit="return confirm('Kosongkan nomor HP, NIK, dan tanggal lahir?')">
        @csrf
        <button type="submit" class="text-xs font-semibold text-error underline underline-offset-2">Kosongkan data profile</button>
      </form>
    </div>
  </section>
  <section class="mb-10">
    <div class="flex justify-between items-end mb-4">
      <h3 class="text-xl font-bold tracking-tight text-blue-900">
        Active Tickets
      </h3>
      <span class="text-xs font-bold uppercase tracking-widest text-primary">{{ $hasScannedBooking ? '0 Upcoming' : '1 Upcoming' }}</span>
    </div>
    @if ($serverTicket && !$hasScannedBooking)
    <div
      class="relative overflow-hidden rounded-4xl bg-[#003063] p-6 text-white shadow-[0_8px_24px_-2px_rgba(0,48,99,0.35)]">
      <div class="flex justify-between items-start mb-8">
        <div>
          <p class="text-[10px] uppercase tracking-widest opacity-70 mb-1">
            Route
          </p>
          <p class="text-lg font-bold">Surabaya → Madura</p>
        </div>
        <div class="bg-secondary-container/20 px-3 py-1 rounded-full">
          <span class="text-[10px] font-bold uppercase tracking-wider text-white">VIP Class</span>
        </div>
      </div>
      <div class="flex justify-between items-center mb-8">
        <div>
          <p class="text-[10px] uppercase tracking-widest opacity-70">
            Departure
          </p>
          <p class="text-xl font-black">09:15</p>
          <p class="text-[10px]">24 OCT 2023</p>
        </div>
        <div class="flex flex-col items-center px-4">
          <span class="material-symbols-outlined opacity-50" data-icon="directions_boat">directions_boat</span>
          <div class="w-16 h-0.5 bg-white/30 my-2 relative">
            <div class="absolute top-1/2 left-0 -translate-y-1/2 w-2 h-2 rounded-full bg-white"></div>
          </div>
        </div>
        <div class="text-right">
          <p class="text-[10px] uppercase tracking-widest opacity-70">
            Arrival
          </p>
          <p class="text-xl font-black">10:00</p>
          <p class="text-[10px]">24 OCT 2023</p>
        </div>
      </div>
      <a class="w-full bg-white hover:bg-[#dbeafe] text-[#003063] py-4 rounded-2xl font-black text-sm uppercase tracking-widest flex items-center justify-center gap-2 active:scale-95 transition-colors no-underline"
        href="{{ url('/my-tickets') }}">
        <span class="material-symbols-outlined" data-icon="qr_code_2">qr_code_2</span>
        View My Tickets
      </a>
    </div>
    @else
      <div class="rounded-3xl border border-surface-container-high bg-surface-container-low p-6 text-center">
        <span class="material-symbols-outlined text-5xl text-on-surface-variant">confirmation_number</span>
        <p class="mt-3 font-bold text-primary">No active tickets</p>
        <p class="mt-1 text-sm text-on-surface-variant">Your ticket has been moved to Booking History.</p>
      </div>
    @endif
  </section>
  <section class="mb-10">
    <h3 class="text-xl font-bold tracking-tight text-blue-900 mb-6">
      Booking History
    </h3>
    <div class="space-y-4">
      @if ($hasScannedBooking)
        <div class="bg-surface-container-low p-5 rounded-3xl flex items-center justify-between gap-4 border {{ $scannedStatus === 'Expired' ? 'border-error/30' : 'border-secondary/30' }}">
          <div class="flex items-center gap-4 min-w-0">
            <div class="w-12 h-12 rounded-2xl {{ $scannedStatus === 'Expired' ? 'bg-error-container text-on-error-container' : 'bg-[#dcfce7] text-[#166534]' }} flex items-center justify-center shadow-sm shrink-0">
              <span class="material-symbols-outlined">{{ $scannedStatus === 'Expired' ? 'event_busy' : 'task_alt' }}</span>
            </div>
            <div class="min-w-0">
              <p class="font-bold text-blue-900 truncate">{{ $scannedBooking['route'] ?? 'Ticket route' }}</p>
              <p class="text-xs text-on-surface-variant">{{ $scannedBooking['departure_date'] ?? 'Date unavailable' }}</p>
              <p class="text-xs text-on-surface-variant truncate">{{ $scannedBooking['name'] }}</p>
            </div>
          </div>
          <span class="inline-block px-2 py-1 rounded-full {{ $scannedStatus === 'Expired' ? 'bg-error-container text-on-error-container' : 'bg-[#dcfce7] text-[#166534]' }} text-[10px] font-bold uppercase tracking-tighter shrink-0">{{ $scannedStatus }}</span>
        </div>
      @endif
      @if (!$hasScannedBooking)
        <div class="rounded-3xl border border-dashed border-surface-container-high bg-white p-8 text-center">
          <span class="material-symbols-outlined text-4xl text-on-surface-variant">confirmation_number</span>
          <p class="mt-3 font-bold text-primary">No booking history</p>
          <p class="mt-1 text-sm text-on-surface-variant">Completed tickets will appear here after scanning.</p>
        </div>
      @endif
    </div>
  </section>
</main>
<div id="editProfileModal" class="hidden fixed inset-0 z-[60] items-end sm:items-center justify-center bg-slate-950/40 px-3 py-3 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="editProfileTitle">
  <div class="w-full max-w-lg max-h-[calc(100dvh-1.5rem)] overflow-y-auto rounded-3xl bg-white shadow-2xl sm:max-h-[90dvh]">
    <div class="sticky top-0 z-10 flex items-center justify-between border-b border-surface-container-high bg-white/95 px-5 py-4 backdrop-blur sm:px-6">
      <div>
        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-secondary">Account</p>
        <h2 id="editProfileTitle" class="mt-1 text-xl font-black text-primary">Edit Profile</h2>
      </div>
      <button id="closeProfileModal" type="button" aria-label="Close edit profile" class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-container-low text-primary hover:bg-surface-container-high">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <form id="editProfileForm" action="{{ route('profile.update') }}" method="POST" class="space-y-4 px-5 py-5 sm:px-6 sm:py-6">
      @csrf
      <div>
        <label for="profileName" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Nama lengkap</label>
        <input id="profileName" name="name" type="text" value="{{ $profile['name'] }}" autocomplete="name" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
      </div>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label for="profilePhone" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Nomor HP</label>
          <input id="profilePhone" name="phone" type="tel" inputmode="numeric" value="{{ $profile['phone'] }}" autocomplete="tel" maxlength="13" pattern="08[0-9]{8,11}" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
        </div>
        <div>
          <label for="profileNik" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">NIK</label>
          <input id="profileNik" name="nik" type="text" inputmode="numeric" value="{{ $profile['nik'] }}" autocomplete="off" maxlength="16" pattern="[0-9]{16}" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
        </div>
      </div>
      <div>
        <label for="profileEmail" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email</label>
        <input id="profileEmail" name="email" type="email" value="{{ $profile['email'] }}" autocomplete="email" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
      </div>
      <div>
        <label for="profileBirthDate" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Tanggal lahir</label>
        <input id="profileBirthDate" name="birth_date" type="date" value="{{ $profile['birth_date'] }}" autocomplete="bday" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
      </div>
      <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <button id="cancelProfileEdit" type="button" class="w-full rounded-xl border border-surface-container-high px-5 py-3.5 text-sm font-bold text-primary hover:bg-surface-container-low sm:w-auto">Batal</button>
        <button type="submit" class="w-full rounded-xl bg-primary px-5 py-3.5 text-sm font-bold text-white hover:bg-primary-container sm:w-auto">Simpan perubahan</button>
      </div>
    </form>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var editProfileBtn = document.getElementById('editProfileBtn');
    var editProfileModal = document.getElementById('editProfileModal');
    var closeProfileModal = document.getElementById('closeProfileModal');
    var cancelProfileEdit = document.getElementById('cancelProfileEdit');
    var editProfileForm = document.getElementById('editProfileForm');

    function setProfileModal(open) {
      editProfileModal.classList.toggle('hidden', !open);
      editProfileModal.classList.toggle('flex', open);
      document.body.classList.toggle('overflow-hidden', open);
      if (open) document.getElementById('profileName').focus();
    }

    editProfileBtn?.addEventListener('click', function () { setProfileModal(true); });
    closeProfileModal?.addEventListener('click', function () { setProfileModal(false); });
    cancelProfileEdit?.addEventListener('click', function () { setProfileModal(false); });
    editProfileModal?.addEventListener('click', function (event) {
      if (event.target === editProfileModal) setProfileModal(false);
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !editProfileModal.classList.contains('hidden')) setProfileModal(false);
    });
    editProfileForm?.addEventListener('submit', function (event) {
      var submitButton = editProfileForm.querySelector('button[type="submit"]');
      if (submitButton) submitButton.disabled = true;
    });
  });
</script>
@endsection

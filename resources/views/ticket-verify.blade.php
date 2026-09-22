@extends('layouts.app')

@section('bodyClass', 'bg-surface-container-low min-h-screen')

@section('content')
@php
  $hasPassenger = filled($ticket['name'] ?? null) && filled($ticket['nik'] ?? null);
@endphp
<div class="min-h-screen px-3 sm:px-6 py-6 sm:py-10 flex items-center justify-center">
  <main class="w-full max-w-md">
    <div class="text-center mb-6">
      <div class="mx-auto mb-4 w-16 h-16 rounded-2xl bg-[#003063] text-[#f4b41a] flex items-center justify-center shadow-lg">
        <span class="material-symbols-outlined text-4xl">verified</span>
      </div>
      <p class="text-[11px] uppercase tracking-[0.3em] font-bold text-secondary">Surabaya-Madura Ferry</p>
      <h1 class="mt-2 text-2xl sm:text-3xl font-black text-primary">Ticket Verified</h1>
      <p class="mt-2 text-sm text-on-surface-variant">This ticket data was securely read from the QR code.</p>
    </div>

    <section class="overflow-hidden rounded-3xl bg-white shadow-[0_12px_32px_-12px_rgba(0,48,99,0.28)] border border-surface-container-high">
      <div class="bg-[#003063] px-4 sm:px-6 py-4 sm:py-5 text-white flex items-center justify-between gap-3">
        <div>
          <p class="text-[10px] uppercase tracking-[0.25em] text-white/70 font-bold">Passenger Ticket</p>
          <h2 class="mt-1 text-lg sm:text-xl font-black">KMP Gili Iyang</h2>
        </div>
        <span class="rounded-full bg-[#f4b41a] px-3 py-1 text-[10px] font-black uppercase tracking-wider text-[#003063]">Valid</span>
      </div>

      @if ($hasPassenger)
        <div class="p-4 sm:p-6 space-y-5">
          <div class="rounded-2xl bg-surface-container-low p-4">
            <p class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-bold">Passenger</p>
            <p class="mt-1 text-lg sm:text-xl font-black text-primary wrap-break-word">{{ $ticket['name'] }}</p>
            <p class="mt-1 text-sm text-on-surface-variant">NIK {{ $ticket['nik'] }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-bold">Phone</p>
              <p class="mt-1 font-bold text-primary wrap-break-word">{{ $ticket['phone'] ?? 'Not provided' }}</p>
            </div>
            <div>
              <p class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-bold">Departure</p>
              <p class="mt-1 font-bold text-primary">{{ $ticket['departure_date'] ?? 'Not selected' }}</p>
            </div>
          </div>

          <div class="flex items-center gap-3 rounded-2xl bg-blue-50 p-4">
            <span class="material-symbols-outlined text-primary">directions_boat</span>
            <div>
              <p class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-bold">Route</p>
              <p class="font-bold text-primary">{{ $ticket['route'] ?? 'Not provided' }}</p>
            </div>
          </div>

          <div class="flex items-center justify-between border-t border-surface-container-high pt-5">
            <div>
              <p class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-bold">Vehicle Plate</p>
              <p class="mt-1 font-bold text-primary">{{ $ticket['vehicle_plate'] ?? 'Tidak ada' }}</p>
            </div>
            <span class="material-symbols-outlined text-secondary text-3xl">qr_code_2</span>
          </div>
        </div>
      @else
        <div class="p-8 text-center">
          <span class="material-symbols-outlined text-5xl text-error">error</span>
          <h2 class="mt-3 text-lg font-bold text-primary">Incomplete ticket data</h2>
          <p class="mt-2 text-sm text-on-surface-variant">Please scan the QR code from a valid ticket.</p>
        </div>
      @endif
    </section>

    <a href="{{ url('/landing') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#003063] px-5 py-4 text-sm font-bold uppercase tracking-widest text-white hover:bg-[#00468c] transition-colors no-underline">
      <span class="material-symbols-outlined">arrow_back</span>
      Back to Home
    </a>
    @if ($hasPassenger)
      <p class="mt-4 text-center text-xs text-on-surface-variant">
        Mengalihkan ke Explore dalam <span id="redirectCountdown" class="font-bold text-primary">3</span> detik...
      </p>
    @endif
  </main>
</div>
@if ($hasPassenger)
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var countdown = document.getElementById('redirectCountdown');
    var seconds = 3;
    var exploreUrl = @json(url('/landing'));
    var timer = window.setInterval(function () {
      seconds -= 1;
      if (countdown) countdown.textContent = seconds;
      if (seconds <= 0) {
        window.clearInterval(timer);
        window.location.replace(exploreUrl);
      }
    }, 1000);
  });
</script>
@endif
@endsection

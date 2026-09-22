@extends('layouts.app')

@section('bodyClass', 'bg-surface min-h-screen')

@section('content')
@php
  $vehicle = request('vehicle', request('serviceType') === 'vehicle' ? null : 'passenger');
  $prices = ['passenger' => '10.000', 'motor' => '15.000', 'car' => '20.000'];
  $labels = ['passenger' => 'Passenger Only', 'motor' => 'Motor', 'car' => 'Mobil'];
  $basePrice = $vehicle ? ($prices[$vehicle] ?? '0') : '0';
  $serviceFee = '5.000';
  $totalPrice = number_format((int) str_replace('.', '', $basePrice) + 5000, 0, ',', '.');
  $vehicleLabel = $vehicle ? ($labels[$vehicle] ?? 'Not selected') : 'Not selected';
  $bookingParams = request()->only(['origin', 'destination', 'departureDate', 'serviceType', 'vehicle']);
  $bookingUrl = url('/booking') . '?' . http_build_query($bookingParams);
@endphp
<header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl shadow-sm px-4 sm:px-6 py-3 sm:py-4 w-full">
  <div class="max-w-5xl mx-auto flex items-center justify-between gap-3">
    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
      <a href="{{ $bookingUrl }}" class="inline-flex items-center justify-center rounded-full bg-surface-container p-2 hover:bg-surface-container-high transition-colors">
        <span class="material-symbols-outlined text-blue-900 dark:text-blue-400">arrow_back</span>
      </a>
      <div>
        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] sm:tracking-[0.3em] text-on-surface-variant mb-1">Payment</p>
        <h1 class="text-base sm:text-xl font-extrabold text-blue-900 dark:text-blue-100 truncate">Surabaya-Madura</h1>
      </div>
    </div>
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary">
      <span class="material-symbols-outlined">lock</span>
    </span>
  </div>
</header>
<main class="max-w-5xl mx-auto px-3 sm:px-6 py-6 sm:py-10 pb-8">
  <div class="space-y-8">
    <div class="rounded-3xl bg-white shadow-[0_8px_24px_-16px_rgba(25,28,30,0.25)] border border-surface-container-high p-4 sm:p-6">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-[0.3em] text-on-surface-variant">Current Step</p>
          <h2 class="text-2xl font-extrabold text-blue-900">Payment</h2>
        </div>
        <div class="inline-flex items-center gap-2 rounded-full bg-secondary/10 px-3 py-2 text-secondary text-[10px] sm:text-xs font-bold uppercase tracking-[0.15em] sm:tracking-[0.3em]">
          <span class="material-symbols-outlined">payment</span>
          Select method
        </div>
      </div>
    </div>
    <div class="grid gap-6 lg:grid-cols-[1.6fr_1fr]">
      <div class="space-y-6">
        <section class="rounded-3xl bg-white border border-surface-container-high p-4 sm:p-6 shadow-sm">
          <div class="mb-4">
            <h3 class="text-lg font-bold text-blue-900">Virtual Account</h3>
            <p class="text-sm text-on-surface-variant">Choose a bank to complete your transfer.</p>
          </div>
          <div class="space-y-4">
            <label class="group flex items-center justify-between gap-4 rounded-3xl border border-transparent bg-surface-container-lowest p-4 transition hover:border-primary/20 cursor-pointer">
              <div class="flex items-center gap-4">
                <div class="w-12 h-8 rounded-2xl bg-white flex items-center justify-center p-1 border border-surface-container-high">
                  <img alt="Mandiri" class="max-h-full max-w-full object-contain"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCAx2k58YQkOqcOvWUwnsBC5PHDIYjqUf0TKpQzrD8DCSZ4q0RD6sDL8TZieV5aM3UmZTQXBmJfCwkV3-B_J-BKJrWEdEcZ8a08IzJ0Hu81V0EkpruwoMFTSXXxzv-EF8Xtvg41v_BDgFdAUzneeXXFdrxkis4GEnUzzX4ZhWl0nO574POHZjmG6TBzTR8HibACdEF1RNcCCxlnSw0iAjg41u5fe6lyAjLVQvzjHIDJrZTARSULk1xVe1A41NMiglHkJE_EhK2mYNwj" />
                </div>
                <div>
                  <p class="font-semibold text-on-surface">Mandiri Virtual Account</p>
                  <p class="text-sm text-on-surface-variant">Automated verification</p>
                </div>
              </div>
              <input class="hidden peer" form="paymentForm" name="payment_method" value="mandiri" type="radio" />
              <div class="w-6 h-6 rounded-full border-2 border-outline-variant peer-checked:border-secondary peer-checked:bg-secondary flex items-center justify-center transition-colors">
                <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
              </div>
            </label>
            <label class="group flex items-center justify-between gap-4 rounded-3xl border border-transparent bg-surface-container-lowest p-4 transition hover:border-primary/20 cursor-pointer">
              <div class="flex items-center gap-4">
                <div class="w-12 h-8 rounded-2xl bg-white flex items-center justify-center p-1 border border-surface-container-high">
                  <img alt="BCA" class="max-h-full max-w-full object-contain"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOVfvYX_-0i2T1qb_dlRDeCN8QlRcvZk2c8W-Aab9XW7GgaRzQcS73NbeKCoXmE9RsTGtaVf06_KsVIuO7Y55O6f3TdAjcRXcUEf9YOizGt-THfd2gpojuB2PZWOr-DeNTU26jOcDZsNH3Fxp0Mf5_lZ1xoe74Qx8gYob68fO7dc4QL__b_wqJyH1iH88FswMJpy9c9UscktrT_RgW332BdWLkz0N36wV1rBcFKB50W9tJkI-1Onx0qXCKHJZf1gZTXAivqVUZ6Tz4" />
                </div>
                <div>
                  <p class="font-semibold text-on-surface">BCA Virtual Account</p>
                  <p class="text-sm text-on-surface-variant">Manual verification not required</p>
                </div>
              </div>
              <input class="hidden peer" form="paymentForm" name="payment_method" value="bca" type="radio" />
              <div class="w-6 h-6 rounded-full border-2 border-outline-variant peer-checked:border-secondary peer-checked:bg-secondary flex items-center justify-center transition-colors">
                <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
              </div>
            </label>
          </div>
        </section>
        <section class="rounded-3xl bg-white border border-surface-container-high p-4 sm:p-6 shadow-sm">
          <div class="mb-4">
            <h3 class="text-lg font-bold text-blue-900">E-wallet</h3>
            <p class="text-sm text-on-surface-variant">Pay using your digital wallet balance</p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label class="group flex flex-col items-center justify-center gap-3 rounded-3xl border border-transparent bg-surface-container-lowest p-5 text-center transition hover:border-primary/20 cursor-pointer">
              <input class="hidden peer" form="paymentForm" name="payment_method" value="ovo" type="radio" />
              <span class="material-symbols-outlined text-4xl text-purple-600">account_balance_wallet</span>
              <p class="font-semibold text-sm text-on-surface">OVO</p>
              <div class="w-4 h-4 rounded-full border-2 border-outline-variant peer-checked:border-secondary peer-checked:bg-secondary flex items-center justify-center">
                <div class="w-1.5 h-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
              </div>
            </label>
            <label class="group flex flex-col items-center justify-center gap-3 rounded-3xl border border-transparent bg-surface-container-lowest p-5 text-center transition hover:border-primary/20 cursor-pointer">
              <input class="hidden peer" form="paymentForm" name="payment_method" value="dana" type="radio" />
              <span class="material-symbols-outlined text-4xl text-blue-500">payments</span>
              <p class="font-semibold text-sm text-on-surface">Dana</p>
              <div class="w-4 h-4 rounded-full border-2 border-outline-variant peer-checked:border-secondary peer-checked:bg-secondary flex items-center justify-center">
                <div class="w-1.5 h-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
              </div>
            </label>
            <label class="group flex flex-col items-center justify-center gap-3 rounded-3xl border border-transparent bg-surface-container-lowest p-5 text-center transition hover:border-primary/20 cursor-pointer">
              <input class="hidden peer" form="paymentForm" name="payment_method" value="shopeepay" type="radio" />
              <span class="material-symbols-outlined text-4xl text-orange-600">shopping_bag</span>
              <p class="font-semibold text-sm text-on-surface">ShopeePay</p>
              <div class="w-4 h-4 rounded-full border-2 border-outline-variant peer-checked:border-secondary peer-checked:bg-secondary flex items-center justify-center">
                <div class="w-1.5 h-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
              </div>
            </label>
          </div>
        </section>
      </div>
      <aside class="rounded-3xl bg-white border border-surface-container-high p-4 sm:p-6 shadow-sm">
        <div class="space-y-5">
          <div>
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-on-surface-variant">Trip Details</p>
            <div class="mt-4 space-y-4">
              <div class="rounded-3xl bg-surface-container p-4">
                <p class="font-semibold text-on-surface">{{ $vehicleLabel }}</p>
                <p class="text-sm text-on-surface-variant">{{ ucfirst(request('origin', 'ujung')) }} to {{ ucfirst(request('destination', 'kamal')) }}</p>
              </div>
              <div class="grid grid-cols-2 gap-3 text-sm text-on-surface-variant">
                <div class="rounded-3xl bg-surface-container p-4">
                  <p class="font-bold text-on-surface">08:30 AM</p>
                  <p>24 Oct 2023</p>
                </div>
                <div class="rounded-3xl bg-surface-container p-4">
                  <p class="font-bold text-on-surface">09:15 AM</p>
                  <p>24 Oct 2023</p>
                </div>
              </div>
            </div>
          </div>
          <div class="rounded-3xl bg-surface-container p-4">
            <div class="flex justify-between text-sm text-on-surface-variant">
                  <span>{{ $vehicleLabel }}</span>
                  <span>Rp {{ $basePrice }}</span>
            </div>
            <div class="flex justify-between text-sm text-on-surface-variant mt-2">
              <span>Service Fee</span>
              <span>Rp 5.000</span>
            </div>
          </div>
          <div class="rounded-3xl bg-white border border-surface-container-high p-4">
            <div class="flex justify-between text-sm uppercase tracking-[0.2em] text-on-surface-variant">
              <span>Total</span>
              <span class="font-black text-secondary">Rp {{ $totalPrice }}</span>
            </div>
          </div>
          <div class="rounded-3xl bg-primary-fixed/10 p-4 text-sm text-on-primary-fixed-variant">
            <div class="flex items-center gap-3 mb-2">
              <span class="material-symbols-outlined">verified_user</span>
              <p class="font-semibold">Secure payment</p>
            </div>
            <p>Your transaction is encrypted and protected by industry standard protocols.</p>
          </div>
        </div>
      </aside>
    </div>
  </div>
  <div class="sticky top-[calc(100vh-7rem)] z-10 sm:top-auto sm:relative">
    <div class="rounded-3xl bg-white border border-surface-container-high p-4 sm:p-6 shadow-sm flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.3em] text-on-surface-variant">Total Payment</p>
        <p class="text-2xl font-black text-primary">Rp {{ $totalPrice }}</p>
      </div>
      <form id="paymentForm" action="{{ route('payment.complete', [], false) }}" method="POST">
        @csrf
        <p id="paymentMethodNotice" class="hidden mb-3 rounded-lg bg-error-container px-4 py-3 text-xs font-semibold text-on-error-container" role="alert">Silakan pilih metode pembayaran terlebih dahulu.</p>
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-full bg-secondary px-6 sm:px-8 py-3.5 sm:py-4 text-xs sm:text-base text-white font-bold uppercase tracking-[0.15em] sm:tracking-[0.2em] shadow-lg shadow-secondary/20 hover:bg-secondary-container transition-colors">
          Pay Now
          <span class="material-symbols-outlined text-xl">keyboard_arrow_right</span>
        </button>
      </form>
    </div>
  </div>
</main>
<script>
  document.getElementById('paymentForm').addEventListener('submit', function (event) {
    var selectedMethod = document.querySelector('input[name="payment_method"]:checked');
    var notice = document.getElementById('paymentMethodNotice');

    if (!selectedMethod) {
      event.preventDefault();
      notice.classList.remove('hidden');
    } else {
      notice.classList.add('hidden');
    }
  });
</script>
@endsection

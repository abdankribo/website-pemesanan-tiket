@extends('layouts.app')

@section('content')
<header
  class="bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl shadow-[0_8px_24px_-2px_rgba(25,28,30,0.06)] fixed top-0 left-0 w-full z-50 flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4">
  <div class="flex items-center gap-2 min-w-0">
    <span class="material-symbols-outlined text-blue-900 dark:text-blue-400">sailing</span>
    <h1 class="font-['Inter'] tracking-tight font-bold text-sm sm:text-lg uppercase text-blue-900 dark:text-blue-400 truncate">
      Surabaya-Madura
    </h1>
  </div>
  <div class="flex items-center gap-1 sm:gap-4 shrink-0">
    <a href="{{ url('/') }}" class="text-[10px] sm:text-sm font-bold uppercase tracking-widest text-blue-900 dark:text-blue-400 hover:underline">
      Dashboard
    </a>
    <button class="hidden sm:block hover:bg-slate-100/50 transition-colors duration-300 p-2 rounded-full">
      <span class="material-symbols-outlined text-blue-900 dark:text-blue-400">notifications</span>
    </button>
  </div>
</header>
<main class="pt-20 sm:pt-24 pb-40 px-3 sm:px-4 max-w-2xl mx-auto">
  @if ($errors->has('booking'))
    <div class="mb-5 rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800" role="alert">
      {{ $errors->first('booking') }}
    </div>
  @endif
  @php
    $serviceType = request('serviceType', 'passenger');
    $profile = auth()->user();
    $serviceLabel = $serviceType === 'vehicle' ? 'Vehicle' : 'Passenger Only';
    $paymentParams = request()->only(['origin', 'destination', 'departureDate', 'serviceType']);
    $paymentParams['vehicle'] = $serviceType === 'vehicle' ? 'motor' : 'passenger';
    $paymentUrl = route('payment') . '?' . http_build_query($paymentParams);
  @endphp
  <section class="mb-5 rounded-xl bg-primary p-4 sm:p-6 text-white shadow-lg">
    <div class="flex items-center justify-between gap-4 mb-5">
      <div>
        <p class="text-[10px] uppercase tracking-widest text-primary-fixed-dim font-bold">Your Booking</p>
        <h2 class="text-xl font-black mt-1">{{ $serviceLabel }}</h2>
      </div>
      <span class="material-symbols-outlined text-secondary-container text-3xl">directions_boat</span>
    </div>
    <div class="grid grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
      <div>
        <p class="text-[10px] uppercase tracking-widest text-primary-fixed-dim">Route</p>
        <p class="font-bold mt-1">{{ ucfirst(request('origin', 'ujung')) }} to {{ ucfirst(request('destination', 'kamal')) }}</p>
      </div>
      <div>
        <p class="text-[10px] uppercase tracking-widest text-primary-fixed-dim">Departure</p>
        <p class="font-bold mt-1">{{ request('departureDate', 'Not selected') }}</p>
      </div>
      <div>
        <p class="text-[10px] uppercase tracking-widest text-primary-fixed-dim">Vehicle</p>
        <p id="selectedVehicleLabel" class="font-bold mt-1">{{ request('vehicle') === 'motor' ? 'Motor' : (request('vehicle') === 'car' ? 'Mobil' : (request('vehicle') === 'passenger' ? 'Not selected' : 'Not selected')) }}</p>
      </div>
    </div>
  </section>
  <p id="bookingNotice" class="hidden mb-6 rounded-lg bg-error-container px-4 py-3 text-sm font-semibold text-on-error-container" role="alert"></p>
  <section class="mb-6">
    <div class="bg-surface-container-low p-1 rounded-xl mb-4">
      <div class="bg-surface-container-lowest p-4 sm:p-6 rounded-lg shadow-sm border border-outline-variant/10">
        <div class="flex items-center gap-2 sm:gap-3 mb-5">
          <span class="material-symbols-outlined text-primary" style="font-variation-settings: &quot;FILL&quot; 1">person</span>
          <h2 class="text-base sm:text-lg font-bold tracking-tight text-primary">
            Lead Passenger Information
          </h2>
        </div>
        <div class="space-y-5">
          <div class="group">
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2 ml-1">Lead
              Passenger Name</label>
            <input
              id="passengerName"
              class="w-full bg-surface-variant/30 border-none rounded-lg p-4 text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all outline-none"
              placeholder="Full name as per ID" value="{{ old('passengerName', $profile->name) }}" type="text" pattern="[A-Za-zÀ-ÿ. ]+" required />
            <p class="mt-1 text-xs text-on-surface-variant">Gunakan nama sesuai KTP. Hanya huruf, spasi, dan titik yang diperbolehkan.</p>
            <p id="nameNotice" class="hidden mt-2 rounded-lg bg-error-container px-3 py-2 text-xs font-semibold text-on-error-container" role="alert">Nama hanya boleh berisi huruf, spasi, dan titik.</p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2 ml-1">ID
                Number (NIK)</label>
              <input
                id="passengerNik"
                class="w-full bg-surface-variant/30 border-none rounded-lg p-4 text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                placeholder="16 digit angka" value="{{ old('passengerNik', $profile->nik ?? '') }}" type="text" inputmode="numeric" minlength="16" maxlength="16" pattern="[0-9]{16}" required />
              <p class="mt-1 text-xs text-on-surface-variant">Wajib 16 digit angka.</p>
              <p id="nikNotice" class="hidden mt-2 rounded-lg bg-error-container px-3 py-2 text-xs font-semibold text-on-error-container" role="alert">Silakan isi NIK dengan tepat 16 digit.</p>
            </div>
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2 ml-1">Phone
                Number</label>
              <input
                id="passengerPhone"
                class="w-full bg-surface-variant/30 border-none rounded-lg p-4 text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                placeholder="08xxxxxxxxxx" value="{{ old('passengerPhone', $profile->phone ?? '') }}" type="tel" inputmode="numeric" maxlength="13" pattern="08[0-9]{8,11}" required />
                <p class="mt-1 text-xs text-on-surface-variant">Gunakan format 08, tanpa +62.</p>
                <p id="phoneNotice" class="hidden mt-2 rounded-lg bg-error-container px-3 py-2 text-xs font-semibold text-on-error-container" role="alert">Silakan isi nomor telepon dengan format 08.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="vehicleSection" class="mb-10">
    <div class="bg-surface-container-low p-1 rounded-xl">
      <div class="bg-surface-container-lowest p-4 sm:p-6 rounded-lg shadow-sm border border-outline-variant/10">
        <p id="vehicleNotice" class="hidden mb-4 rounded-lg bg-error-container px-4 py-3 text-sm font-semibold text-on-error-container" role="alert">
          Silakan pilih Motor atau Mobil terlebih dahulu.
        </p>
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: &quot;FILL&quot; 1">directions_car</span>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-primary">
              Vehicle Information
            </h2>
          </div>
          <span
            class="bg-primary-fixed text-on-primary-fixed-variant px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Selected</span>
        </div>
        <div class="space-y-5">
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2 ml-1">Vehicle
              Category</label>
            <div class="grid grid-cols-3 gap-3">
              <button type="button" data-vehicle="passenger"
                class="vehicle-option flex flex-col items-center justify-center py-4 rounded-xl border-2 border-outline-variant/20 text-on-surface-variant transition-all duration-200">
                <span class="material-symbols-outlined mb-1">directions_walk</span>
                <span class="text-[10px] font-bold uppercase">Jalan Kaki</span>
              </button>
              <button type="button" data-vehicle="motor"
                class="vehicle-option flex flex-col items-center justify-center py-4 rounded-xl border-2 border-outline-variant/20 text-on-surface-variant transition-all duration-200">
                <span class="material-symbols-outlined mb-1">two_wheeler</span>
                <span class="text-[10px] font-bold uppercase">Motor</span>
              </button>
              <button type="button" data-vehicle="car"
                class="vehicle-option flex flex-col items-center justify-center py-4 rounded-xl border-2 border-outline-variant/20 text-on-surface-variant transition-all duration-200">
                <span class="material-symbols-outlined mb-1">directions_car</span>
                <span class="text-[10px] font-bold uppercase">Car</span>
              </button>
            </div>
          </div>
          <div id="vehiclePlateField" class="hidden">
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2 ml-1">Vehicle
              Plate Number</label>
            <input
              id="vehiclePlate"
              class="w-full bg-surface-variant/30 border-none rounded-lg p-4 text-on-surface uppercase placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all outline-none"
              placeholder="e.g. KT 9899 LK" type="text" pattern="[A-Za-z]{1,2}[ ]?[0-9]{4}[ ]?[A-Za-z]+" />
            <p id="plateNotice" class="hidden mt-2 rounded-lg bg-error-container px-3 py-2 text-xs font-semibold text-on-error-container" role="alert">Format plat: 1-2 huruf, 4 angka, lalu minimal 1 huruf. Contoh: KT 9899 LK.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="fixed bottom-0 left-0 w-full z-50">
    <div
      class="bg-primary/95 backdrop-blur-2xl shadow-[0_-8px_24px_-2px_rgba(25,28,30,0.2)] px-4 sm:px-6 pt-3 sm:pt-4 pb-[max(1rem,env(safe-area-inset-bottom))] border-t border-white/15">
      <div class="max-w-2xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <div id="totalPayable" class="hidden flex-col items-start">
          <span class="text-[10px] font-bold uppercase tracking-widest text-white/70">Total Payable</span>
          <div class="flex items-baseline gap-1">
            <span class="text-sm font-bold text-white/70">IDR</span>
            <span id="totalPrice" class="text-2xl font-black text-white tracking-tighter">10.000</span>
          </div>
        </div>
        <a id="paymentLink" class="w-full md:w-auto bg-linear-to-br from-secondary to-secondary-container text-on-secondary px-5 sm:px-8 py-3.5 sm:py-4 rounded-xl font-bold text-xs sm:text-sm uppercase tracking-widest shadow-lg shadow-secondary/20 hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 no-underline"
          href="{{ $paymentUrl }}">
          Continue to Payment
          <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
      </div>
    </div>
  </div>
</main>
<div class="fixed top-40 -left-20 w-64 h-64 bg-primary/5 rounded-full blur-[100px] -z-10"></div>
<div class="fixed bottom-40 -right-20 w-64 h-64 bg-secondary/5 rounded-full blur-[100px] -z-10"></div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var options = document.querySelectorAll('.vehicle-option');
    var selectedLabel = document.querySelector('.bg-primary-fixed.text-on-primary-fixed-variant');
    var vehicleSection = document.getElementById('vehicleSection');
    var vehiclePlateField = document.getElementById('vehiclePlateField');
    var totalPrice = document.getElementById('totalPrice');
    var paymentLink = document.getElementById('paymentLink');
    var totalPayable = document.getElementById('totalPayable');
    var selectedVehicleLabel = document.getElementById('selectedVehicleLabel');
    var bookingNotice = document.getElementById('bookingNotice');
    var vehicleNotice = document.getElementById('vehicleNotice');
    var nikNotice = document.getElementById('nikNotice');
    var phoneNotice = document.getElementById('phoneNotice');
    var nameNotice = document.getElementById('nameNotice');
    var plateNotice = document.getElementById('plateNotice');
    var passengerNik = document.getElementById('passengerNik');
    var notices = [bookingNotice, vehicleNotice, nameNotice, nikNotice, phoneNotice, plateNotice];
    var serviceType = @json($serviceType);
    var requestedVehicle = @json(request('vehicle'));
    var selectedVehicle = requestedVehicle && requestedVehicle !== 'passenger' ? requestedVehicle : null;
    var prices = {
      passenger: '10.000',
      motor: '15.000',
      car: '20.000'
    };
    var serviceFee = 5000;
    var platePattern = /^[A-Z]{1,2} ?\d{4} ?[A-Z]+$/;

    passengerNik.addEventListener('input', function () {
      passengerNik.value = passengerNik.value.replace(/\D/g, '').slice(0, 16);
    });

    document.getElementById('passengerPhone').addEventListener('input', function () {
      this.value = this.value.replace(/\D/g, '').slice(0, 13);
    });

    document.getElementById('passengerName').addEventListener('input', function () {
      this.value = this.value.replace(/[^\p{L}. ]/gu, '').replace(/\s+/g, ' ');
    });

    document.getElementById('vehiclePlate').addEventListener('input', function () {
      this.value = this.value.toUpperCase().replace(/[^A-Z0-9 ]/g, '').replace(/\s+/g, ' ');
    });

    function updatePrice(vehicle) {
      var basePrice = Number((prices[vehicle] || (serviceType === 'vehicle' ? '0' : prices.passenger)).replace('.', ''));
      totalPrice.textContent = (basePrice + serviceFee).toLocaleString('id-ID');
      var paymentUrl = new URL(paymentLink.href);
      if (vehicle) {
        paymentUrl.searchParams.set('vehicle', vehicle);
      } else {
        paymentUrl.searchParams.delete('vehicle');
      }
      paymentLink.href = paymentUrl.toString();
      if (selectedVehicleLabel) {
        selectedVehicleLabel.textContent = vehicle === 'motor' ? 'Motor' : (vehicle === 'car' ? 'Mobil' : (vehicle === 'passenger' ? 'Jalan Kaki' : 'Not selected'));
      }
      vehiclePlateField.classList.toggle('hidden', vehicle !== 'motor' && vehicle !== 'car');
    }

    function bookingIsComplete() {
      var name = document.getElementById('passengerName').value.trim();
      var nik = document.getElementById('passengerNik').value.trim();
      var phone = document.getElementById('passengerPhone').value.trim();
      var plate = document.getElementById('vehiclePlate').value.trim();

      if (!name || !/^\d{16}$/.test(nik) || !/^08\d{8,11}$/.test(phone) || !selectedVehicle) {
        return false;
      }

      return (selectedVehicle === 'passenger') || platePattern.test(plate);
    }

    function updateTotalVisibility() {
      var isComplete = bookingIsComplete();
      totalPayable.classList.toggle('hidden', !isComplete);
      totalPayable.classList.toggle('flex', isComplete);
    }

    if (selectedVehicle) {
      updatePrice(selectedVehicle);
    } else {
      updatePrice(null);
    }

    options.forEach(function (button) {
      button.addEventListener('click', function () {
        options.forEach(function (btn) {
          btn.classList.remove('border-primary', 'bg-primary/5', 'text-primary');
          btn.classList.add('border-outline-variant/20', 'text-on-surface-variant');
        });
        button.classList.add('border-primary', 'bg-primary/5', 'text-primary');
        button.classList.remove('border-outline-variant/20', 'text-on-surface-variant');
        selectedVehicle = button.dataset.vehicle;
        updatePrice(button.dataset.vehicle);
        updateTotalVisibility();
        vehicleNotice.classList.add('hidden');
        if (selectedLabel) {
          selectedLabel.textContent = 'Selected';
        }
      });
    });

    ['passengerName', 'passengerNik', 'passengerPhone', 'vehiclePlate'].forEach(function (id) {
      document.getElementById(id).addEventListener('input', updateTotalVisibility);
    });

    paymentLink.addEventListener('click', function (event) {
      var requiredFields = [
        {
          element: document.getElementById('passengerName'),
          label: 'nama penumpang',
          notice: nameNotice,
          validate: function (value) { return /^(?=.*\p{L})[\p{L}. ]+$/u.test(value); }
        },
        {
          element: document.getElementById('passengerNik'),
          label: 'NIK 16 digit',
          notice: nikNotice,
          validate: function (value) { return /^\d{16}$/.test(value); }
        },
        {
          element: document.getElementById('passengerPhone'),
          label: 'nomor telepon dengan format 08',
          notice: phoneNotice,
          validate: function (value) { return /^08\d{8,11}$/.test(value); }
        }
      ];

      if (selectedVehicle === 'motor' || selectedVehicle === 'car') {
        requiredFields.push({
          element: document.getElementById('vehiclePlate'),
          label: 'plat nomor kendaraan',
          notice: plateNotice,
          validate: function (value) { return platePattern.test(value.toUpperCase()); }
        });
      }

      var missingField = requiredFields.find(function (field) {
        var value = field.element.value.trim();
        return !value || (field.validate && !field.validate(value));
      });

      if (!missingField && !selectedVehicle) {
        missingField = {
          element: vehicleSection,
          label: 'jenis kendaraan atau jalan kaki',
          notice: vehicleNotice
        };
      }

      if (missingField) {
        event.preventDefault();
        totalPayable.classList.add('hidden');
        totalPayable.classList.remove('flex');
        notices.forEach(function (notice) { notice.classList.add('hidden'); });
        missingField.notice.textContent = 'Silakan isi ' + missingField.label + ' terlebih dahulu.';
        missingField.notice.classList.remove('hidden');
        missingField.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        if (missingField.element.focus) {
          missingField.element.focus();
        }
      } else {
        var bookingUrl = new URL(paymentLink.href);
        bookingUrl.searchParams.set('passengerName', document.getElementById('passengerName').value.trim());
        bookingUrl.searchParams.set('passengerNik', document.getElementById('passengerNik').value.trim());
        bookingUrl.searchParams.set('passengerPhone', document.getElementById('passengerPhone').value.trim());
        bookingUrl.searchParams.set('vehicle', selectedVehicle);
        if (selectedVehicle === 'motor' || selectedVehicle === 'car') {
          bookingUrl.searchParams.set('vehiclePlate', document.getElementById('vehiclePlate').value.trim());
        } else {
          bookingUrl.searchParams.delete('vehiclePlate');
        }
        paymentLink.href = bookingUrl.toString();
        totalPayable.classList.remove('hidden');
        event.preventDefault();
        window.location.assign(bookingUrl.toString());
      }
    });

    updateTotalVisibility();
  });
</script>
@endsection

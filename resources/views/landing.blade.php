@extends('layouts.app')

@section('bodyClass', 'bg-surface-bright min-h-screen pb-24')

@section('content')
<nav class="fixed top-0 left-0 w-full z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl shadow-[0_8px_24px_-2px_rgba(25,28,30,0.06)] flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4">
  <div class="flex items-center gap-2 min-w-0">
    <span class="material-symbols-outlined text-blue-900 dark:text-blue-400">sailing</span>
    <h1 class="font-['Inter'] tracking-tight font-bold text-sm sm:text-lg uppercase text-blue-900 dark:text-blue-400 truncate">
      Surabaya-Madura
    </h1>
  </div>
  <div class="flex items-center gap-1 sm:gap-4 shrink-0">
    <button id="headerBookNow" type="submit" form="bookingSearchForm" class="px-3 sm:px-4 py-2 rounded-full bg-primary text-white text-xs sm:text-sm font-bold hover:bg-primary-container transition-colors">Book Now</button>
    <button class="hidden sm:block p-2 rounded-full hover:bg-slate-100/50 transition-colors duration-300">
      <span class="material-symbols-outlined text-blue-900 dark:text-blue-400">notifications</span>
    </button>
  </div>
</nav>
<section class="relative pt-20 sm:pt-24 pb-36 sm:pb-48 hero-gradient overflow-hidden">
  <div class="absolute inset-0 opacity-20">
    <img class="absolute inset-0 w-full h-full object-cover"
      alt="atmospheric wide shot of a modern ferry cruising through calm deep blue ocean waters during the blue hour with glowing ship lights"
      src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPQ6YnLASBzqTts95kWJgk26T4Sl6zpr0OLGyc8Uj_uZp6AVzu0fkQyHbwtYM-zD9M6Vxs2jck_M3sXexX2hsY9qyVip5O0jjib895LDnP8VgEL-2oefjstV8UsgLdw1BO3l_EKvs6CAzFjuWx5vPrI87S_D76kzhe6z1X4CdvnWIPT7ZSGfeLlWHwiWVqTh2n1nbVZrJZ_q7WlxWDRDak71lklzLFqlg8youu8kUyoxxYCWDARpmitIQXNhmYc-uQ22QBhJ14lBAz" />
  </div>
  <div class="container mx-auto px-4 sm:px-6 relative z-10">
    <div class="max-w-2xl">
      <span class="inline-block px-3 sm:px-4 py-1 rounded-full bg-secondary-container text-on-secondary-fixed text-[10px] font-bold uppercase tracking-widest mb-5">Maritime Excellence</span>
      <h2 class="text-white text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tighter leading-none mb-5">
        Bridging the <br /><span class="italic text-primary-fixed-dim">Madura Strait.</span>
      </h2>
      <p class="text-primary-fixed text-base sm:text-lg max-w-md font-light leading-relaxed opacity-90">
        Premium ferry services connecting Ujung Port and Kamal Port with hourly departures and high-end terminal facilities.
      </p>
    </div>
  </div>
</section>
<main class="container mx-auto px-4 sm:px-6 -mt-24 sm:-mt-32 relative z-20">
  <div class="bg-surface-container-lowest rounded-xl shadow-[0_8px_24px_-2px_rgba(25,28,30,0.06)] p-5 sm:p-8 md:p-10">
  <form id="bookingSearchForm" action="{{ url('/search') }}" method="GET">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="space-y-2">
        <label class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1">Origin</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-outline text-xl">location_on</span>
          </div>
          <select id="origin" name="origin" class="w-full bg-surface-container-low border-none rounded-xl py-4 pl-12 pr-4 font-semibold text-on-surface focus:ring-2 focus:ring-primary-container">
            <option value="ujung" selected>Ujung Port</option>
            <option value="kamal">Kamal Port</option>
          </select>
        </div>
      </div>
      <div class="space-y-2">
        <label class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1">Destination</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-outline text-xl">near_me</span>
          </div>
          <select id="destination" name="destination" class="w-full bg-surface-container-low border-none rounded-xl py-4 pl-12 pr-4 font-semibold text-on-surface focus:ring-2 focus:ring-primary-container">
            <option value="kamal" selected>Kamal Port</option>
            <option value="ujung">Ujung Port</option>
          </select>
        </div>
      </div>
      <div class="space-y-2">
        <label class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1">Departure Date</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
          </div>
          <input id="departureDate" name="departureDate" data-locked-date="{{ now()->toDateString() }}" class="w-full bg-surface-container-low border-none rounded-xl py-4 pl-4 pr-4 font-semibold text-on-surface focus:ring-2 focus:ring-primary-container appearance-none" type="date" min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" required />
          <p id="departureDateNotice" class="hidden mt-2 rounded-lg bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-800" role="alert">Silakan pilih tanggal keberangkatan yang valid.</p>
          <p class="mt-2 text-xs font-semibold text-on-surface-variant">Tanggal keberangkatan tidak dapat diubah setelah ditentukan.</p>
          @error('departureDate')
            <p class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-800">{{ $message }}</p>
          @enderror
        </div>
      </div>
      <div class="space-y-2">
        <label class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1">Service Type</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-outline text-xl">directions_boat</span>
          </div>
          <select id="serviceType" name="serviceType" class="w-full bg-surface-container-low border-none rounded-xl py-4 pl-12 pr-4 font-semibold text-on-surface focus:ring-2 focus:ring-primary-container appearance-none">
            <option value="passenger" selected>Passenger Only</option>
            <option value="vehicle">Vehicle (Car/Bike)</option>
          </select>
        </div>
      </div>
    </div>

    <div class="mt-7 sm:mt-10 flex flex-col gap-4 md:flex-row items-center justify-between pt-6 sm:pt-8 border-t border-surface-container-high">
      <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <button id="continueBookingButton" type="submit" class="w-full sm:w-auto px-6 sm:px-12 py-4 sm:py-5 rounded-xl border border-surface-container-high bg-white text-slate-900 font-bold tracking-widest uppercase text-xs sm:text-sm shadow-sm hover:bg-surface-container transition-all duration-200 flex items-center justify-center gap-3 no-underline">
          Continue to Booking
          <span class="material-symbols-outlined">schedule</span>
        </button>
      </div>
    </div>
  </form>
  </div>
</main>
<section class="container mx-auto px-4 sm:px-6 mt-16 sm:mt-24 mb-16">
  <h3 class="text-2xl sm:text-3xl font-black tracking-tight text-primary mb-7 sm:mb-10">Premium Experience</h3>
  <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-auto md:h-125">
    <div class="md:col-span-7 bg-surface-container-low rounded-xl overflow-hidden relative group">
      <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="luxury ferry lounge interior" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnu7rroYhfi_49yIY1nVpjlsFgYYWJiDMhpbf78ltGG9kihYXg_tK2Rq6xwF7iYFXUZTxuIROUblyCbr5S0jzhgxHtsVpZamLjb8jefjFE50gC8ihKBORmJ7VuUgIbt_vYVgs19eO8H0wYr9_mSjQaaDPLo2QGHKsQQuJk3NlSpWgmTOxvUP2K3z-UL5g5QedvLARI2tCkuhkZo4ItTFk6cBCuRUd328CZVrVqNJo0dOilEA6WNs4YUaWb2ig21HkXTrmpwghigDPN" />
      <div class="absolute inset-0 bg-linear-to-t from-primary/90 to-transparent"></div>
      <div class="absolute bottom-0 left-0 p-8">
        <span class="bg-primary-fixed-dim text-on-primary-fixed px-3 py-1 rounded-full text-[10px] font-black uppercase mb-4 inline-block">New Fleet</span>
        <h4 class="text-white text-3xl font-bold leading-tight">Comfort Class <br />Now Available</h4>
        <p class="text-primary-fixed/80 text-sm mt-2 max-w-sm">Enjoy climate-controlled cabins and reclining leather seats on all major crossings.</p>
      </div>
    </div>
    <div class="md:col-span-5 flex flex-col gap-6">
      <div class="flex-1 bg-secondary/10 rounded-xl p-8 flex flex-col justify-between border border-secondary/5">
        <div>
          <span class="material-symbols-outlined text-secondary text-4xl mb-4" style="font-variation-settings: &quot;FILL&quot; 1">timer</span>
          <h4 class="text-secondary font-bold text-xl uppercase tracking-tighter">Fast Loading</h4>
          <p class="text-on-secondary-fixed-variant text-sm mt-2 font-medium opacity-80">Dedicated vehicle ramps mean 40% faster boarding and departure cycles.</p>
        </div>
      </div>
      <div class="flex-1 bg-surface-container-lowest rounded-xl p-8 shadow-sm flex flex-col justify-between group cursor-pointer hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
          <span class="material-symbols-outlined text-primary-container text-4xl" style="font-variation-settings: &quot;FILL&quot; 1">qr_code_2</span>
          <span class="material-symbols-outlined text-outline opacity-0 group-hover:opacity-100 transition-opacity">open_in_new</span>
        </div>
        <div>
          <h4 class="text-primary font-bold text-xl uppercase tracking-tighter">Digital Check-in</h4>
          <p class="text-on-surface-variant text-sm mt-2">Skip the ticket booth. Use your QR code for instant gantry access.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="container mx-auto px-6 mb-32">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
    <div class="order-2 md:order-1">
      <h3 class="text-[12px] font-black uppercase tracking-[0.2em] text-secondary mb-4">Strategic Connection</h3>
      <h4 class="text-4xl font-bold text-primary mb-6 leading-tight">The Gateway to <br />Madura Island</h4>
      <p class="text-on-surface-variant leading-relaxed mb-8">Operating since 1980, the Surabaya-Madura crossing remains the vital lifeline for logistics and travel between Java and Madura. Our modern fleet ensures safety and reliability across the 3km strait.</p>
      <div class="grid grid-cols-2 gap-4">
        <div class="bg-surface-container px-6 py-4 rounded-xl">
          <span class="block text-primary font-black text-2xl tracking-tighter">24/7</span>
          <span class="text-[10px] font-bold uppercase text-on-surface-variant tracking-widest">Operation</span>
        </div>
        <div class="bg-surface-container px-6 py-4 rounded-xl">
          <span class="block text-primary font-black text-2xl tracking-tighter">15m</span>
          <span class="text-[10px] font-bold uppercase text-on-surface-variant tracking-widest">Avg. Trip</span>
        </div>
      </div>
    </div>
    <div class="order-1 md:order-2 rounded-3xl overflow-hidden shadow-2xl h-100 border-8 border-white">
      <img class="w-full h-full object-cover" alt="stylized map view of the Madura Strait showing the connection between Surabaya and Kamal with maritime navigation elements" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCE-jTsVnEoMo9hF4_KVmDWKrvT3gXJmOhhdy22yEV27JQq_VlZgFAOKNoOWCJUlZfhpgPIEB3H_Zjf3bCK3ryMRcRLYyfIVgfZokJVIkFHrG429cEq9UxrbimLleCzCm1QKtdWb445pqlyw9kbz8cvFx-fAhvC5q2X8BJfuZyjqOTLoMINct-_4m4DRalBYcHNQCFwownZxecKFogdSSW5O6uyXIFkEFLpb5VQwgDpiiV3_6tTp0Q2a8xcftmH9RMD8-tX3naKKb1j" />
    </div>
  </div>
</section>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var origin = document.getElementById('origin');
    var destination = document.getElementById('destination');
    var bookingSearchForm = document.getElementById('bookingSearchForm');
    var departureDate = document.getElementById('departureDate');
    var departureDateNotice = document.getElementById('departureDateNotice');
    var headerBookNow = document.getElementById('headerBookNow');
    var continueBookingButton = document.getElementById('continueBookingButton');
    var lockedDate = departureDate.dataset.lockedDate;

    // Prevent selecting same port for origin and destination
    function syncPorts(changed, other) {
      if (changed.value === other.value) {
        // swap to the first available other option
        for (var i = 0; i < other.options.length; i++) {
          if (other.options[i].value !== changed.value) {
            other.value = other.options[i].value;
            break;
          }
        }
      }
    }

    origin.addEventListener('change', function () { syncPorts(origin, destination); });
    destination.addEventListener('change', function () { syncPorts(destination, origin); });

    function updateDateLock() {
      var dateChanged = departureDate.value !== lockedDate;
      headerBookNow.disabled = dateChanged;
      continueBookingButton.disabled = dateChanged;
      headerBookNow.classList.toggle('opacity-50', dateChanged);
      continueBookingButton.classList.toggle('opacity-50', dateChanged);
      departureDateNotice.textContent = dateChanged
        ? 'Tanggal keberangkatan tidak dapat diubah. Kembalikan ke ' + lockedDate + ' untuk melanjutkan.'
        : 'Silakan pilih tanggal keberangkatan yang valid.';
      departureDateNotice.classList.toggle('hidden', !dateChanged);
    }

    departureDate.addEventListener('change', updateDateLock);

    bookingSearchForm.addEventListener('submit', function (event) {
      var today = new Date().toISOString().split('T')[0];
      if (departureDate.value !== lockedDate || !departureDate.value || departureDate.value < today) {
        event.preventDefault();
        departureDateNotice.textContent = departureDate.value !== lockedDate
          ? 'Tanggal keberangkatan tidak dapat diubah. Kembalikan ke ' + lockedDate + ' untuk melanjutkan.'
          : !departureDate.value
          ? 'Silakan isi tanggal keberangkatan terlebih dahulu.'
          : 'Tanggal keberangkatan tidak boleh sebelum hari ini.';
        departureDateNotice.classList.remove('hidden');
        departureDate.focus();
      } else {
        departureDateNotice.classList.add('hidden');
      }
    });

    updateDateLock();

  });
</script>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center pt-3 pb-8 px-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur-2xl shadow-[0_-8px_24px_-2px_rgba(25,28,30,0.08)] rounded-t-3xl border-none">
  <a class="flex flex-col items-center justify-center bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-100 rounded-2xl px-5 py-2 active:scale-90 transition-transform duration-150" href="{{ url('/landing') }}">
    <span class="material-symbols-outlined mb-1" style="font-variation-settings: &quot;FILL&quot; 1">explore</span>
    <span class="font-['Inter'] text-[10px] uppercase tracking-widest font-bold">Explore</span>
  </a>
  <a class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 px-5 py-2 hover:text-blue-800 dark:hover:text-blue-200 active:scale-90 transition-transform duration-150" href="{{ auth()->check() ? route('account') : route('login') }}">
    <span class="material-symbols-outlined mb-1">person</span>
    <span class="font-['Inter'] text-[10px] uppercase tracking-widest font-bold">Account</span>
  </a>
</nav>
@endsection

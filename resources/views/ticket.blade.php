@extends('layouts.app')

@section('bodyClass', 'bg-surface-container-low font-body text-on-surface min-h-screen pb-28')

@section('content')
@php
  $booking = session('booking_data', []);
  $departureDate = !empty($booking['departureDate'])
    ? \Carbon\Carbon::parse($booking['departureDate'])->format('d M Y')
    : 'Not selected';
  $origin = ucfirst($booking['origin'] ?? 'ujung');
  $destination = ucfirst($booking['destination'] ?? 'kamal');
  $ticketId = session('ticket_id');
  $ticketData = [
    'Nama' => $booking['passengerName'] ?? 'Not provided',
    'NIK' => $booking['passengerNik'] ?? 'Not provided',
    'Telepon' => $booking['passengerPhone'] ?? 'Not provided',
    'Rute' => $origin . ' - ' . $destination,
    'Tanggal keberangkatan' => $departureDate,
    'Plat kendaraan' => $booking['vehiclePlate'] ?? 'Tidak ada',
  ];
  $scanData = [
    'name' => $booking['passengerName'] ?? 'Not provided',
    'nik' => $booking['passengerNik'] ?? 'Not provided',
    'phone' => $booking['passengerPhone'] ?? 'Not provided',
    'route' => $origin . ' - ' . $destination,
    'departure_date' => $departureDate,
    'vehicle_plate' => $booking['vehiclePlate'] ?? 'Tidak ada',
  ];
  $qrBaseUrl = rtrim(config('app.qr_base_url') ?: url('/'), '/');
  $qrPayload = $qrBaseUrl . '/ticket/verify?' . http_build_query(['ticket' => $ticketId]);
  $qrFallbackUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=2&data=' . urlencode($qrPayload);
@endphp
<style>
  .ticket-cutout {
    position: relative;
  }

  .ticket-cutout::before,
  .ticket-cutout::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: #f2f4f6;
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
  }

  .ticket-cutout::before {
    left: -10px;
  }

  .ticket-cutout::after {
    right: -10px;
  }

  .dashed-line {
    background-image: linear-gradient(to right, #c2c6d2 50%, rgba(255, 255, 255, 0) 0%);
    background-position: bottom;
    background-size: 8px 1px;
    background-repeat: repeat-x;
  }
</style>
<header class="bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl fixed top-0 w-full z-50 flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4 shadow-[0_8px_24px_-2px_rgba(25,28,30,0.06)]">
  <div class="flex items-center gap-2 min-w-0">
    <span class="material-symbols-outlined text-blue-900">sailing</span>
    <h1 class="text-sm sm:text-xl font-black italic tracking-tighter text-blue-900 uppercase truncate">Surabaya-Madura</h1>
  </div>
  <div class="hidden sm:flex items-center gap-4">
    <span class="material-symbols-outlined text-slate-500">notifications</span>
  </div>
</header>
<main class="pt-20 sm:pt-24 pb-32 px-3 sm:px-6 flex flex-col items-center max-w-lg mx-auto">
  <div class="w-full mb-6 sm:mb-8">
    <span class="text-[10px] uppercase tracking-widest font-bold text-secondary mb-1 block">Your Boarding Pass</span>
    <h2 class="text-2xl sm:text-3xl font-extrabold text-primary tracking-tight">Ready for departure</h2>
  </div>
  <div class="w-full bg-surface-container-lowest rounded-4xl shadow-[0_8px_24px_-2px_rgba(25,28,30,0.06)] overflow-hidden">
    <div class="p-5 sm:p-8 bg-[#003063] text-white">
      <div class="flex justify-between items-center mb-6">
        <div>
          <p class="text-[10px] uppercase tracking-widest opacity-70 font-bold">Vessel</p>
          <p class="text-lg font-bold">KMP Gili Iyang</p>
        </div>
        <div class="bg-[#f4b41a] text-[#003063] rounded-full px-3 py-1">
          <span class="text-[10px] uppercase tracking-widest font-bold">Economy Class</span>
        </div>
      </div>
      <div class="flex justify-between items-center">
        <div class="text-center">
            <p class="text-3xl font-black tracking-tighter">{{ strtoupper(substr($origin, 0, 3)) }}</p>
          <p class="text-[10px] uppercase tracking-widest opacity-70">Surabaya</p>
        </div>
        <div class="flex-1 px-4 flex flex-col items-center">
          <span class="material-symbols-outlined text-[#f4b41a]">directions_boat</span>
          <div class="w-full h-px bg-white/20 my-2 relative">
            <div class="absolute inset-0 bg-[#f4b41a]"></div>
          </div>
        </div>
        <div class="text-center">
            <p class="text-3xl font-black tracking-tighter">{{ strtoupper(substr($destination, 0, 3)) }}</p>
          <p class="text-[10px] uppercase tracking-widest opacity-70">Madura</p>
        </div>
      </div>
    </div>
    <div class="ticket-cutout relative h-6 bg-surface-container-lowest">
      <div class="dashed-line absolute top-1/2 left-4 right-4 h-px"></div>
    </div>
    <div class="p-5 sm:p-8 pt-3 sm:pt-4">
      <div class="grid grid-cols-2 gap-x-3 gap-y-5 sm:gap-y-6 mb-6 sm:mb-8">
        <div>
          <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Passenger</p>
            <p class="text-sm font-bold text-primary">{{ $booking['passengerName'] ?? 'Not provided' }}</p>
        </div>
        <div class="text-right">
          <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">NIK</p>
          <p class="text-sm font-bold text-primary">{{ $booking['passengerNik'] ?? 'Not provided' }}</p>
        </div>
        <div>
          <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Phone</p>
          <p class="text-sm font-bold text-primary">{{ $booking['passengerPhone'] ?? 'Not provided' }}</p>
        </div>
        <div class="text-right">
          <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Departure</p>
          <p class="text-sm font-bold text-primary">{{ $departureDate }}</p>
        </div>
      </div>
      <div class="flex flex-col items-center justify-center p-4 sm:p-6 bg-surface-container-low rounded-3xl border border-outline-variant/15">
        <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm mb-4">
          <img id="ticketQrCodeImage" alt="Ticket QR Code" class="w-36 h-36 sm:w-40 sm:h-40"
            src="{{ $qrFallbackUrl }}" />
        </div>
        <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Ticket ID</p>
        <p class="text-sm font-mono font-bold tracking-widest text-primary">SM-8829-X04</p>
      </div>
    </div>
  </div>
  <div class="w-full mt-6 sm:mt-8 flex gap-3 sm:gap-4">
    <button id="downloadTicketBtn"
      class="flex-1 min-w-0 bg-[#003063] hover:bg-[#00468c] text-white py-3.5 sm:py-4 px-3 rounded-xl font-bold flex items-center justify-center gap-2 shadow-[0_8px_24px_-2px_rgba(0,48,99,0.35)] active:scale-95 transition-all">
      <span class="material-symbols-outlined text-lg">download</span>
      <span class="text-[11px] sm:text-sm uppercase tracking-wider truncate">Download Ticket</span>
    </button>
    <a href="{{ url('/booking') }}"
      class="w-16 bg-white text-primary border border-outline-variant/30 py-4 rounded-xl flex items-center justify-center shadow-sm active:scale-95 transition-transform">
      <span class="material-symbols-outlined">share</span>
    </a>
  </div>
  <div class="mt-10 p-6 bg-blue-50/50 rounded-2xl border border-primary/5 flex items-start gap-4">
    <span class="material-symbols-outlined text-primary">info</span>
    <div>
      <p class="text-xs font-bold text-primary uppercase tracking-tight mb-1">Boarding Policy</p>
      <p class="text-xs text-on-surface-variant leading-relaxed">Please arrive at the terminal at least 30 minutes before departure. Have your physical ID ready for verification.</p>
    </div>
  </div>
</main>
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center pt-3 pb-8 px-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur-2xl shadow-[0_-8px_24px_-2px_rgba(25,28,30,0.08)]">
  <a class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 px-5 py-2 hover:text-blue-800 active:scale-90 transition-transform duration-150"
    href="{{ url('/landing') }}">
    <span class="material-symbols-outlined">explore</span>
    <span class="font-['Inter'] text-[10px] uppercase tracking-widest font-bold mt-1">Explore</span>
  </a>
  <a class="flex flex-col items-center justify-center bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-100 rounded-2xl px-5 py-2 active:scale-90 transition-transform duration-150"
    href="{{ url('/ticket') }}">
    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">confirmation_number</span>
    <span class="font-['Inter'] text-[10px] uppercase tracking-widest font-bold mt-1">Tickets</span>
  </a>
  <a class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 px-5 py-2 hover:text-blue-800 active:scale-90 transition-transform duration-150"
    href="{{ auth()->check() ? route('account') : route('login') }}">
    <span class="material-symbols-outlined">person</span>
    <span class="font-['Inter'] text-[10px] uppercase tracking-widest font-bold mt-1">Account</span>
  </a>
</nav>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.4/build/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script>
  const qrPayload = @json($qrPayload);

  if (window.QRCode && typeof window.QRCode.toDataURL === 'function') {
    window.QRCode.toDataURL(qrPayload, { width: 320, margin: 2 })
      .then(function (dataUrl) {
        document.getElementById('ticketQrCodeImage').src = dataUrl;
      })
      .catch(function (error) {
        console.error('Error generating QR code:', error);
      });
  }

  async function downloadQrCodePdf() {
    const imgEl = document.getElementById('ticketQrCodeImage');
    if (!imgEl) return;
    try {
      const response = await fetch(imgEl.src, { mode: 'cors' });
      const blob = await response.blob();
      const bitmap = await createImageBitmap(blob);
      const canvas = document.createElement('canvas');
      canvas.width = bitmap.width;
      canvas.height = bitmap.height;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(bitmap, 0, 0);
      const imageData = canvas.toDataURL('image/png');
      const { jsPDF } = window.jspdf;
      const pdf = new jsPDF({ unit: 'mm', format: 'a4' });
      const pageWidth = pdf.internal.pageSize.getWidth();
      const pageHeight = pdf.internal.pageSize.getHeight();
      const margin = 20;
      const availableWidth = pageWidth - margin * 2;
      const availableHeight = pageHeight - margin * 2;
      const aspectRatio = bitmap.width / bitmap.height;
      let renderWidth = availableWidth;
      let renderHeight = renderWidth / aspectRatio;
      if (renderHeight > availableHeight) {
        renderHeight = availableHeight;
        renderWidth = renderHeight * aspectRatio;
      }
      const x = (pageWidth - renderWidth) / 2;
      const y = (pageHeight - renderHeight) / 2;
      pdf.addImage(imageData, 'PNG', x, y, renderWidth, renderHeight);
      pdf.save('ticket-qr-code.pdf');
    } catch (error) {
      console.error('Error generating PDF:', error);
      alert('Unable to download ticket. Please try again.');
    }
  }

  document.getElementById('downloadTicketBtn')?.addEventListener('click', downloadQrCodePdf);
</script>
@endsection
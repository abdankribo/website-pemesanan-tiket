@extends('layouts.app')

@section('bodyClass', 'bg-surface-bright min-h-screen pb-28')

@section('content')
@php
  $hasLatestTicket = session('ticket_id')
    && \Illuminate\Support\Facades\DB::table('tickets')->where('ticket_id', session('ticket_id'))->exists();
@endphp
<header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl shadow-[0_8px_24px_-2px_rgba(25,28,30,0.06)] flex justify-between items-center px-6 py-4">
  <div class="flex items-center gap-3">
    <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-surface-container p-2 hover:bg-surface-container-high transition-colors">
      <span class="material-symbols-outlined text-blue-900 dark:text-blue-400">arrow_back</span>
    </a>
    <div>
      <h1 class="font-['Inter'] tracking-tight font-bold text-lg uppercase text-blue-900 dark:text-blue-400">My Tickets</h1>
      <p class="text-xs text-on-surface-variant">Tickets you ordered from your account</p>
    </div>
  </div>
  <div class="flex items-center gap-3">
    @if ($hasLatestTicket)
    <a href="{{ url('/ticket') }}" class="inline-flex items-center px-4 py-2 rounded-full bg-secondary text-white text-sm font-bold hover:bg-secondary-container transition-colors">
      Latest E-Ticket
    </a>
    @endif
  </div>
</header>
<main class="pt-24 px-6 pb-32 max-w-4xl mx-auto space-y-6">
  <section class="rounded-4xl bg-surface-container-low p-6 shadow-[0_8px_24px_-2px_rgba(25,28,30,0.08)]">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h2 class="text-2xl font-bold text-blue-900">Booked Tickets</h2>
        <p class="text-sm text-on-surface-variant">Review your active and past tickets in one place.</p>
      </div>
      <span class="inline-flex items-center rounded-full bg-primary/10 text-primary px-4 py-2 text-xs font-bold uppercase tracking-[0.2em]">0 Tickets</span>
    </div>
    <div class="rounded-3xl border border-dashed border-surface-container-high bg-white p-10 text-center">
      <span class="material-symbols-outlined text-5xl text-on-surface-variant">confirmation_number</span>
      <p class="mt-4 font-bold text-primary">No tickets yet</p>
      <p class="mt-1 text-sm text-on-surface-variant">Your booked tickets will appear here.</p>
    </div>
  </section>
</main>
@endsection
@extends('layouts.app')

@section('bodyClass', 'bg-surface-container-low min-h-screen')

@section('content')
<main class="min-h-screen px-4 py-8 sm:flex sm:items-center sm:justify-center sm:px-6">
  <section class="mx-auto w-full max-w-md">
    <a href="{{ route('login') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-bold text-primary no-underline hover:text-primary-container">
      <span class="material-symbols-outlined text-lg">arrow_back</span>
      Kembali ke login
    </a>
    <div class="rounded-3xl bg-white px-5 py-7 shadow-[0_16px_40px_-18px_rgba(0,48,99,0.3)] sm:px-8 sm:py-9">
      <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white">
        <span class="material-symbols-outlined text-3xl">lock_reset</span>
      </div>
      <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-secondary">Account recovery</p>
      <h1 class="mt-2 text-2xl font-black tracking-tight text-primary">Lupa password?</h1>
      <p class="mt-2 text-sm leading-relaxed text-on-surface-variant">Masukkan email akun Anda. Kami akan mengirimkan link untuk membuat password baru.</p>
      @if (session('status'))
        <div class="mt-5 rounded-xl bg-[#dcfce7] px-4 py-3 text-sm font-semibold text-[#166534]">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="mt-5 rounded-xl bg-error-container px-4 py-3 text-sm font-semibold text-on-error-container">{{ $errors->first() }}</div>
      @endif
      <form action="{{ route('password.email', [], false) }}" method="POST" class="mt-6 space-y-5">
        @csrf
        <div>
          <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required autofocus>
        </div>
        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3.5 text-sm font-bold text-white hover:bg-primary-container">
          Kirim link reset
          <span class="material-symbols-outlined text-lg">send</span>
        </button>
      </form>
    </div>
  </section>
</main>
@endsection

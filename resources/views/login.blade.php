@extends('layouts.app')

@section('bodyClass', 'bg-surface-container-low min-h-screen')

@section('content')
<main class="min-h-screen px-4 py-8 sm:flex sm:items-center sm:justify-center sm:px-6">
  <section class="mx-auto w-full max-w-md">
    <a href="{{ route('landing') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-bold text-primary no-underline hover:text-primary-container">
      <span class="material-symbols-outlined text-lg">arrow_back</span>
      Kembali ke Explore
    </a>

    <div class="overflow-hidden rounded-3xl bg-white shadow-[0_16px_40px_-18px_rgba(0,48,99,0.3)]">
      <div class="bg-primary px-5 py-7 text-white sm:px-8 sm:py-9">
        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-secondary-container text-primary">
          <span class="material-symbols-outlined text-3xl">lock</span>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-primary-fixed-dim">Surabaya-Madura</p>
        <h1 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl">Masuk untuk memesan</h1>
        <p class="mt-2 text-sm leading-relaxed text-primary-fixed">Gunakan akun Anda untuk melanjutkan pemesanan tiket kapal.</p>
      </div>

      <form action="{{ route('login.submit', [], false) }}" method="POST" class="space-y-5 px-5 py-6 sm:px-8 sm:py-8">
        @csrf
        @if ($errors->any())
          <div class="rounded-xl bg-error-container px-4 py-3 text-sm font-semibold text-on-error-container" role="alert">
            {{ $errors->first() }}
          </div>
        @endif
        <div>
          <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required autofocus>
        </div>
        <div>
          <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Password</label>
          <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Masukkan password" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
          <a href="{{ route('password.request') }}" class="mt-2 inline-block text-xs font-semibold text-primary no-underline hover:text-primary-container">Lupa password?</a>
        </div>
        <label class="flex items-center gap-2 text-sm text-on-surface-variant">
          <input name="remember" type="checkbox" value="1" class="rounded border-outline-variant text-primary focus:ring-primary">
          Ingat saya di perangkat ini
        </label>
        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3.5 text-sm font-bold text-white transition-colors hover:bg-primary-container">
          Masuk dan lanjutkan
          <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </button>
        <div class="flex items-center gap-3 text-xs text-on-surface-variant">
          <span class="h-px flex-1 bg-surface-container-high"></span>
          atau
          <span class="h-px flex-1 bg-surface-container-high"></span>
        </div>
        <a href="{{ route('google.redirect', [], false) }}" class="flex w-full items-center justify-center gap-3 rounded-xl border border-surface-container-high px-5 py-3.5 text-sm font-bold text-on-surface no-underline transition-colors hover:bg-surface-container-low">
          <span class="flex h-5 w-5 items-center justify-center rounded-full bg-white text-sm font-black text-[#4285F4] shadow-sm">G</span>
          Lanjutkan dengan Google
        </a>
        <p class="text-center text-xs leading-relaxed text-on-surface-variant">Akun demo tersedia melalui seeder aplikasi.</p>
        <p class="text-center text-sm text-on-surface-variant">Belum punya akun?
          <a href="{{ route('register') }}" class="font-bold text-primary no-underline hover:text-primary-container">Buat akun</a>
        </p>
      </form>
    </div>
  </section>
</main>
@endsection

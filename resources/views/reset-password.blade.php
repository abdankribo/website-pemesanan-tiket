@extends('layouts.app')

@section('bodyClass', 'bg-surface-container-low min-h-screen')

@section('content')
<main class="min-h-screen px-4 py-8 sm:flex sm:items-center sm:justify-center sm:px-6">
  <section class="mx-auto w-full max-w-md">
    <div class="rounded-3xl bg-white px-5 py-7 shadow-[0_16px_40px_-18px_rgba(0,48,99,0.3)] sm:px-8 sm:py-9">
      <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white">
        <span class="material-symbols-outlined text-3xl">key</span>
      </div>
      <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-secondary">Account recovery</p>
      <h1 class="mt-2 text-2xl font-black tracking-tight text-primary">Buat password baru</h1>
      <form action="{{ route('password.update', [], false) }}" method="POST" class="mt-6 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
          <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required autofocus>
        </div>
        <div>
          <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Password baru</label>
          <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
        </div>
        <div>
          <label for="password_confirmation" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Konfirmasi password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi password" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
        </div>
        @if ($errors->any())
          <div class="rounded-xl bg-error-container px-4 py-3 text-sm font-semibold text-on-error-container">{{ $errors->first() }}</div>
        @endif
        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3.5 text-sm font-bold text-white hover:bg-primary-container">
          Simpan password baru
          <span class="material-symbols-outlined text-lg">check</span>
        </button>
      </form>
    </div>
  </section>
</main>
@endsection

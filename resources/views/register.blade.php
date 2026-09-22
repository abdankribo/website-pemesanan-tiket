@extends('layouts.app')

@section('bodyClass', 'bg-surface-container-low min-h-screen')

@section('content')
<main class="min-h-screen px-4 py-8 sm:flex sm:items-center sm:justify-center sm:px-6">
  <section class="mx-auto w-full max-w-md">
    <a href="{{ route('login') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-bold text-primary no-underline hover:text-primary-container">
      <span class="material-symbols-outlined text-lg">arrow_back</span>
      Kembali ke login
    </a>

    <div class="overflow-hidden rounded-3xl bg-white shadow-[0_16px_40px_-18px_rgba(0,48,99,0.3)]">
      <div class="bg-primary px-5 py-7 text-white sm:px-8 sm:py-9">
        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-secondary-container text-primary">
          <span class="material-symbols-outlined text-3xl">person_add</span>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-primary-fixed-dim">Surabaya-Madura</p>
        <h1 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl">Buat akun baru</h1>
        <p class="mt-2 text-sm leading-relaxed text-primary-fixed">Daftar untuk menyimpan data diri dan memesan tiket lebih cepat.</p>
      </div>

      <form action="{{ route('register.submit', [], false) }}" method="POST" class="space-y-5 px-5 py-6 sm:px-8 sm:py-8">
        @csrf
        @if ($errors->any())
          <div class="rounded-xl bg-error-container px-4 py-3 text-sm font-semibold text-on-error-container" role="alert">
            <ul class="space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <div>
          <label for="name" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Nama lengkap</label>
          <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" placeholder="Nama sesuai identitas" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required autofocus>
        </div>
        <div>
          <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
        </div>
        <div>
          <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Password</label>
          <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
        </div>
        <div>
          <label for="password_confirmation" class="mb-2 block text-xs font-bold uppercase tracking-wider text-on-surface-variant">Konfirmasi password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi password" class="w-full rounded-xl border-0 bg-surface-container-low p-3.5 text-on-surface outline-none ring-primary/20 focus:ring-2" required>
        </div>
        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3.5 text-sm font-bold text-white transition-colors hover:bg-primary-container">
          Buat akun dan lanjutkan
          <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </button>
        <p class="text-center text-sm text-on-surface-variant">Sudah punya akun?
          <a href="{{ route('login') }}" class="font-bold text-primary no-underline hover:text-primary-container">Masuk</a>
        </p>
      </form>
    </div>
  </section>
</main>
@endsection

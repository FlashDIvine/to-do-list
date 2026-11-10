@extends('layouts.app', ['title' => 'Daftar Akun Baru'])

@section('content')
<div class="max-w-md mx-auto my-10 bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Daftar Akun Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Daftarkan akun untuk mulai mengorganisir tugas harian Anda</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        <!-- REASONING UKK: CSRF token mencegah submission ilegal dari situs pihak ketiga -->
        @csrf

        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-slate-400"
                placeholder="Ahmad Fauzan">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-slate-400"
                placeholder="nama@sekolah.sch.id">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi (Min. 6 Karakter)</label>
            <input type="password" name="password" id="password" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-slate-400"
                placeholder="••••••••">
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-slate-400"
                placeholder="••••••••">
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition duration-150 shadow-md shadow-indigo-100">
            Daftarkan Akun
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 underline">Masuk di Sini</a>
    </div>
</div>
@endsection


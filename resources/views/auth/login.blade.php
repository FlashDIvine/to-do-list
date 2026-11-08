@extends('layouts.app', ['title' => 'Masuk ke Akun'])

@section('content')
<div class="max-w-md mx-auto my-10 bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Masuk ke Akun</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola daftar tugas berbobot Anda dengan rapi</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        <!-- REASONING UKK: Proteksi CSRF wajib ada pada seluruh form otentikasi -->
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-slate-400"
                placeholder="nama@sekolah.sch.id">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
            <input type="password" name="password" id="password" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-slate-400"
                placeholder="••••••••">
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                <span class="text-slate-600 text-xs">Ingat saya</span>
            </label>
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition duration-150 shadow-md shadow-indigo-100">
            Masuk Sekarang
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 underline">Daftar Akun Baru</a>
    </div>
</div>
@endsection


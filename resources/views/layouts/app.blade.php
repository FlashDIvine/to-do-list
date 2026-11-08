<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'To-Do List Berbobot' }} - UKK RPL</title>
    <!-- REASONING UKK: Tailwind CSS via CDN memungkinkan styling modern instan tanpa dependensi Node.js / npm run dev saat live demo -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-200">
                        ✓
                    </div>
                    <div>
                        <span class="text-lg font-bold text-slate-900 tracking-tight">TaskPriority</span>
                        <span class="text-xs block text-slate-400 font-medium">To-Do List Berbobot UKK RPL</span>
                    </div>
                </div>

                <!-- User Profile & Action -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="text-right hidden sm:block">
                            <span class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-slate-500 block">{{ auth()->user()->email }}</span>
                        </div>

                        <!-- Form Logout -->
                        <form action="{{ route('logout') }}" method="POST">
                            <!-- REASONING UKK: Token CSRF wajib disertakan pada form dengan metode non-GET untuk proteksi serangan Cross-Site Request Forgery -->
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3.5 py-1.5 border border-slate-300 text-xs font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 hover:text-red-600 hover:border-red-300 transition duration-150 ease-in-out shadow-sm">
                                Keluar
                            </button>
                        </form>
                    @else
                        <div class="space-x-2">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 px-3 py-1.5">Masuk</a>
                            <a href="{{ route('register') }}" class="text-sm font-semibold bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Alert Sukses -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start space-x-3 shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Flash Alert Validasi Error -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold">Terjadi kesalahan pada input data:</p>
                        <ul class="mt-1 list-disc list-inside text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-12">
        <div class="max-w-6xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} TaskPriority &bull; Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>


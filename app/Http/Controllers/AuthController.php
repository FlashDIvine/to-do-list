<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses otentikasi login pengguna.
     */
    public function login(Request $request)
    {
        // REASONING UKK: Validasi format email dan keberadaan password sebelum query ke database dieksekusi
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // REASONING UKK: Auth::attempt secara otomatis mencocokkan kredensial dan melakukan verifikasi hash password
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // REASONING UKK: Regenerasi session ID wajib dilakukan untuk mencegah serangan Session Fixation
            $request->session()->regenerate();

            return redirect()->intended(route('tasks.index'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // REASONING UKK: Mengembalikan pesan error jika kredensial salah tanpa membocorkan apakah email atau password yang tidak cocok (prinsip Least Information)
        return back()->withErrors([
            'email' => 'Kombinasi email dan password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan formulir pendaftaran akun baru.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Simpan pengguna baru ke database dan lakukan auto-login.
     */
    public function register(Request $request)
    {
        // REASONING UKK: Validasi input registrasi, pastikan email unik dan konfirmasi password cocok
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // REASONING UKK: Enkripsi satu arah password menggunakan Hash::make demi keamanan data akun
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // REASONING UKK: Otomatis melakukan login untuk kenyamanan UX pengguna setelah registrasi berhasil
        Auth::login($user);
        // REASONING UKK: Regenerasi session ID baru untuk akun yang baru saja diotentikasi
        $request->session()->regenerate();

        return redirect()->route('tasks.index')->with('success', 'Registrasi berhasil! Selamat datang di To-Do List Berbobot.');
    }

    /**
     * Logout pengguna dan hancurkan session.
     */
    public function logout(Request $request)
    {
        // REASONING UKK: Memutus sesi otentikasi pengguna aktif
        Auth::logout();

        // REASONING UKK: Menghancurkan session lama agar tidak bisa disalahgunakan (session hijacking)
        $request->session()->invalidate();

        // REASONING UKK: Me-refresh token CSRF untuk menjamin form login berikutnya mendapatkan token baru yang valid
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}


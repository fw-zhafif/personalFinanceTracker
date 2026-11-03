<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- FITUR REGISTRASI ---
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Input Form
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Wajib ada input password_confirmation
        ]);

        // 2. Buat User Baru dengan Hashing Password
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']), // Mengenkripsi password dengan algoritma Bcrypt
        ]);

        // 3. Otomatis Login setelah berhasil Registrasi
        Auth::login($user);

        // 4. Redirect ke halaman transaksi
        return redirect()->route('transactions.index')->with('success', 'Akun berhasil dibuat dan Anda telah masuk!');
    }

    // --- FITUR LOGIN ---
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Mencoba Autentikasi (Verifikasi Hash & Email)
        if (Auth::attempt($credentials)) {
            // Mencegah serangan 'Session Fixation' dengan memperbarui ID session
            $request->session()->regenerate();

            return redirect()->intended('/transactions')->with('success', 'Berhasil login!');
        }

        // 3. Jika gagal, kembalikan dengan pesan error k       husus email/password
        return back()->withErrors([
            'email' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    // --- FITUR LOGOUT ---
    public function logout(Request $request)
    {
        // 1. Keluar dari Guard Auth
        Auth::logout();

        // 2. Hancurkan seluruh data Session yang tersimpan di server
        $request->session()->invalidate();

        // 3. Buat ulang CSRF Token untuk sesi berikutnya
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
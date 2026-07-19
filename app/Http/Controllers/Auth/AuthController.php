<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {

        return view('auth.login');

    }


    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->has('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            $name = $user->name ?? $user->username ?? 'Pengguna';

            // Redirect berdasarkan role pengguna
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('success', "Selamat datang kembali, {$name}! Anda berhasil masuk sebagai Administrator."),
                'opd'   => redirect()->route('opd.dashboard')->with('success', "Selamat datang, {$name}! Anda berhasil masuk sebagai OPD."),
                default => redirect('/login'),
            };

        }

        return back()->withErrors([
            'email' => 'Username/Email atau password salah.',
        ])->onlyInput('email');

    }


    /**
     * Proses logout pengguna.
     */
    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('info', 'Anda telah berhasil keluar dari sistem.');

    }

}

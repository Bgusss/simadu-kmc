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

            // Redirect berdasarkan role pengguna
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'opd'   => redirect()->route('opd.dashboard'),
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

        return redirect('/login');

    }

}

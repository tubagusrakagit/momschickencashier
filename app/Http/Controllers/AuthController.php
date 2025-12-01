<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Cek apakah input adalah email atau username
        $field = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $credentials['username'], 'password' => $credentials['password']])) {
            
            $request->session()->regenerate();

            // --- PERUBAHAN DI SINI (LOGIKA REDIRECT) ---
            $role = Auth::user()->role;

            if ($role === 'admin') {
                // Jika Admin, arahkan ke Dashboard
                return redirect()->intended(route('dashboard'));
            } else {
                // Jika Kasir, arahkan ke halaman Transaksi
                return redirect()->intended(route('kasir'));
            }
        }

        return back()->withInput()->with('error', 'Username atau Password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

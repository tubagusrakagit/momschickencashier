<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil role pengguna yang sedang login
        $userRole = Auth::user()->role;
        
        // 3. Cek apakah role pengguna sesuai dengan role yang disyaratkan
        if ($userRole === $role) {
            return $next($request);
        }

        // 4. Jika tidak cocok, arahkan ke halaman yang sesuai atau kembali
        // Admin selalu diizinkan jika role tidak sesuai, tapi kita biarkan kasir terbatasi.
        if ($userRole === 'admin') {
            return $next($request);
        }

        // Jika user bukan role yang diminta dan bukan admin, kembalikan ke halaman sebelumnya
        return back()->with('error', 'Akses Ditolak. Anda tidak memiliki izin ' . $role . '.');
    }
}

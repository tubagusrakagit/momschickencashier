<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // PASTIKAN BLOK INI SUDAH BENAR
    ->withMiddleware(function (Middleware $middleware): void {
        // Mendaftarkan alias menggunakan metode 'alias' (bukan withAliases)
        $middleware->alias(['role', \App\Http\Middleware\RoleMiddleware::class]); // <-- FIX DI BARIS INI
        
        // Di sini Anda juga bisa menambahkan middleware global jika diperlukan
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
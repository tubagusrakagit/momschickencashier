<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. LOGIKA TOTAL PENJUALAN & PERTUMBUHAN ---
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $salesToday = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $salesYesterday = Transaction::whereDate('created_at', $yesterday)->sum('total_amount');

        // Hitung persentase pertumbuhan (cegah pembagian dengan nol)
        $growthPercentage = 0;
        if ($salesYesterday > 0) {
            $growthPercentage = (($salesToday - $salesYesterday) / $salesYesterday) * 100;
        } elseif ($salesToday > 0) {
            $growthPercentage = 100; // Jika kemarin 0 dan hari ini ada, anggap naik 100%
        }

        // --- 2. LOGIKA PRODUK BARU MINGGU INI ---
        $newProductsCount = Product::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        // --- 3. LOGIKA TRANSAKSI HARI INI (Total Count) ---
        $transactionsTodayCount = Transaction::whereDate('created_at', $today)->count();

        // --- 4. AMBIL 5 TRANSAKSI TERAKHIR (REAL DB) ---
        // Kita asumsikan ada relasi 'details' atau kita ambil data utamanya saja
        $recentTransactions = Transaction::latest()
            ->take(5)
            ->get();

        // --- 5. SUSUN DATA UNTUK VIEW ---
        $stats = [
            'total_sales_today' => $salesToday,
            'sales_growth' => round($growthPercentage, 1), // Bulatkan 1 desimal
            'total_products' => Product::count(),
            'new_products_week' => $newProductsCount,
            'last_transaction' => Transaction::latest()->first(),
            'transactions_today_count' => $transactionsTodayCount,
        ];

        return view('dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentTransactions, // Kirim data transaksi asli
        ]);
    }
}
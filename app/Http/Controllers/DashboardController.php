<?php

namespace App\Http\Controllers;

use App\Models\Product; // Digunakan untuk menghitung total produk
use App\Models\Transaction; // Digunakan untuk statistik transaksi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Statistik Utama
        $stats = [
            // Hitung Total Penjualan Hari Ini (Contoh Query)
            'total_sales_today' => Transaction::whereDate('created_at', today())->sum('total_amount'),
            
            // Hitung Jumlah Produk Aktif (sesuai data seeding Anda)
            'total_products' => Product::count(),
            
            // Hitung Transaksi Terakhir (Jika ada, ambil waktu terakhir)
            'last_transaction' => Transaction::latest()->first(),
        ];
        
        // 2. Ambil Aktivitas Terbaru (Data Dummy untuk sementara)
        $recentActivities = [
            [ 'type' => 'Order', 'details' => 'Order #783 (Dine In)', 'amount' => 120000, 'time' => '2 min ago', 'status' => 'SUCCESS'],
            [ 'type' => 'Order', 'details' => 'Order #782 (Take Away)', 'amount' => 85000, 'time' => '5 min ago', 'status' => 'SUCCESS'],
            [ 'type' => 'Inventory', 'details' => 'Product: Ayam Paha Atas Stock Alert', 'amount' => null, 'time' => '15 min ago', 'status' => 'ALERT'],
            [ 'type' => 'Order', 'details' => 'Order #780 (Dine In)', 'amount' => 200000, 'time' => '12 min ago', 'status' => 'SUCCESS'],
        ];

        return view('dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
        ]);
    }
}

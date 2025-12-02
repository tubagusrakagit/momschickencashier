<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail; // Import model detail transaksi
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Admin dengan data statistik, grafik, dan produk terlaris.
     */
    public function index()
    {
        // --- 1. Persiapan Tanggal ---
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startDate30Days = Carbon::today()->subDays(29); 
        $startDate8Weeks = Carbon::today()->subWeeks(7)->startOfWeek(); 

        // --- 2. Kumpulan Statistik ---
        $totalSalesToday = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $totalSalesYesterday = Transaction::whereDate('created_at', $yesterday)->sum('total_amount');

        // Hitung Pertumbuhan Penjualan Hari Ini vs Kemarin
        $salesGrowth = 0;
        if ($totalSalesYesterday > 0) {
            $salesGrowth = round((($totalSalesToday - $totalSalesYesterday) / $totalSalesYesterday) * 100, 1);
        } elseif ($totalSalesToday > 0) {
            $salesGrowth = 100;
        }
        
        // Ambil Transaksi Terbaru untuk List Aktivitas
        $recentActivities = Transaction::orderBy('created_at', 'desc')->limit(10)->get();
            
        // Kumpulan data statistik untuk card
        $stats = [
            'total_sales_today' => $totalSalesToday,
            'sales_growth' => $salesGrowth,
            'total_products' => Product::count(),
            'new_products_week' => Product::where('created_at', '>=', $startOfWeek)->count(),
            'transactions_today_count' => Transaction::whereDate('created_at', $today)->count(),
            'last_transaction' => Transaction::orderBy('created_at', 'desc')->first(),
        ];
        
        // --- 3. PRODUK TERLARIS (Top Selling Products) ---
        $topProducts = TransactionDetail::select(
                'products.name as product_name', 
                DB::raw('SUM(transaction_details.quantity) as total_qty_sold')
            )
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->orderBy('total_qty_sold', 'desc')
            ->limit(5)
            ->get();


        // --- 4. Persiapan Data Grafik Harian (30 Hari Terakhir) ---
        $salesDataDaily = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereBetween('created_at', [$startDate30Days->startOfDay(), $today->endOfDay()])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        // Membuat array lengkap 30 hari
        $labelsDaily = [];
        $dataOmsetDaily = [];
        
        $currentDate = $startDate30Days->copy();
        while ($currentDate <= $today) {
            $dateString = $currentDate->toDateString();
            $labelsDaily[] = $currentDate->format('d M'); 
            $dataOmsetDaily[] = $salesDataDaily->get($dateString)['total'] ?? 0;
            $currentDate->addDay();
        }

        // Data Chart.js Harian
        $chartDataDaily = [
            'labels' => $labelsDaily,
            'datasets' => [
                [
                    'label' => 'Omset Harian (Rp)',
                    'data' => $dataOmsetDaily,
                    'backgroundColor' => 'rgba(217, 54, 48, 0.8)',
                    'borderColor' => 'rgba(217, 54, 48, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4, 
                    'fill' => true
                ]
            ]
        ];
        
        // --- 5. Persiapan Data Grafik Mingguan (8 Minggu Terakhir) ---
        $salesDataWeekly = Transaction::select(
                DB::raw('WEEK(created_at, 1) as week_number'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('created_at', '>=', $startDate8Weeks->startOfDay())
            ->groupBy('year', 'week_number')
            ->orderBy('year', 'asc')
            ->orderBy('week_number', 'asc')
            ->get();
            
        $labelsWeekly = [];
        $dataOmsetWeekly = [];
        $weekSalesMap = $salesDataWeekly->mapWithKeys(function ($item) {
            return ["{$item->year}-{$item->week_number}" => $item->total];
        });

        $currentWeekStart = $startDate8Weeks->copy();
        $weekCount = 0;

        while ($weekCount < 8) {
            $weekNumber = $currentWeekStart->weekOfYear;
            $year = $currentWeekStart->year;
            $key = "{$year}-{$weekNumber}";

            $weekLabel = 'Minggu ' . $currentWeekStart->format('W');
            $weekEnd = $currentWeekStart->copy()->endOfWeek();
            $labelsWeekly[] = $currentWeekStart->format('d M') . ' - ' . $weekEnd->format('d M');

            $dataOmsetWeekly[] = $weekSalesMap->get($key) ?? 0;

            $currentWeekStart->addWeek();
            $weekCount++;
        }
        
        // Data Chart.js Mingguan
        $weeklyChartData = [
            'labels' => $labelsWeekly,
            'datasets' => [
                [
                    'label' => 'Omset Mingguan (Rp)',
                    'data' => $dataOmsetWeekly,
                    'backgroundColor' => 'rgba(255, 195, 0, 0.8)',
                    'borderColor' => 'rgba(255, 195, 0, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.2, 
                    'fill' => true
                ]
            ]
        ];


        return view('dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'topProducts' => $topProducts, // Tambahkan produk terlaris
            'chartDataDaily' => json_encode($chartDataDaily), 
            'chartDataWeekly' => json_encode($weeklyChartData), 
        ]);
    }
}
@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
    
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Overview Kasir</h1>
    
    <!-- KARTU STATISTIK & TOP PRODUCT (4 KOLOM) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Card 1: Penjualan & Pertumbuhan -->
        <div class="bg-white p-6 rounded-3xl shadow-sm transform transition duration-300 hover:scale-[1.02] border-t-4 border-brand-red">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Penjualan Hari Ini</p>
                    <p class="mt-1 text-4xl font-extrabold text-gray-900">
                        Rp {{ number_format($stats['total_sales_today'], 0, ',', '.') }} 
                    </p>
                </div>
                <div class="p-3 rounded-full bg-brand-red/10 text-brand-red shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
            </div>
            
            <div class="mt-4 text-xs font-semibold flex items-center">
                @if($stats['sales_growth'] >= 0)
                    <span class="text-green-500 mr-1">▲ {{ $stats['sales_growth'] }}%</span>
                @else
                    <span class="text-brand-red mr-1">▼ {{ abs($stats['sales_growth']) }}%</span>
                @endif
                <span class="text-gray-500 font-normal">vs. Kemarin</span>
            </div>
        </div>

        <!-- Card 2: Produk & Produk Baru -->
        <div class="bg-white p-6 rounded-3xl shadow-sm transform transition duration-300 hover:scale-[1.02] border-t-4 border-brand-gold">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Produk Aktif</p>
                    <p class="mt-1 text-4xl font-extrabold text-gray-900">
                        {{ number_format($stats['total_products']) }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-brand-gold/10 text-brand-gold shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-10"/></svg>
                </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-gray-700 flex items-center">
                <span class="text-xs font-semibold text-green-500 mr-1">+{{ $stats['new_products_week'] }} Baru</span>
                <span class="text-gray-500 font-normal">minggu ini</span>
            </div>
        </div>

        <!-- Card 3: Transaksi Terakhir -->
        <div class="bg-white p-6 rounded-3xl shadow-sm transform transition duration-300 hover:scale-[1.02] border-t-4 border-gray-400">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Transaksi Terakhir</p>
                    <p class="mt-1 text-2xl font-extrabold text-gray-900">
                        @if($stats['last_transaction'])
                            {{ $stats['last_transaction']->created_at->diffForHumans() }}
                        @else
                            Belum ada
                        @endif
                    </p>
                </div>
                <div class="p-3 rounded-full bg-gray-400/10 text-gray-600 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-gray-700 flex items-center">
                <span class="text-xs font-semibold text-blue-500 mr-1">{{ $stats['transactions_today_count'] }} Transaksi</span>
                <span class="text-gray-500 font-normal">berhasil hari ini</span>
            </div>
        </div>
        
        <!-- Card 4: PRODUK TERLARIS (BARU) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm transform transition duration-300 hover:scale-[1.02] border-t-4 border-green-500 flex flex-col justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Produk Terlaris (#1)</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900 truncate" title="{{ $topProducts->first()->product_name ?? 'N/A' }}">
                    {{ $topProducts->first()->product_name ?? 'N/A' }}
                </p>
            </div>
            
            <div class="mt-4">
                <p class="text-xs font-semibold text-gray-700">Terlaris Lainnya:</p>
                <ul class="text-xs text-gray-500 space-y-1 mt-1">
                    @forelse($topProducts->skip(1) as $index => $product)
                        <li class="flex justify-between items-center border-b border-dashed border-gray-100 pb-0.5">
                            <span class="truncate">#{{ $index + 2 }}. {{ $product->product_name }}</span>
                            <span class="font-bold text-gray-700">{{ $product->total_qty_sold }}x</span>
                        </li>
                    @empty
                        <li class="text-center text-gray-400">Belum ada data penjualan.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    <!-- AREA GRAFIK TERPISAH (2 Baris) -->
    
    <!-- Baris 1: Grafik Harian (Daily Chart) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tren Penjualan Harian (30 Hari)</h2>
            <div style="height: 300px;">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        <!-- Aktivitas Terbaru (DATA ASLI DARI DB) -->
        <div class="lg:col-span-1 bg-white p-6 rounded-3xl shadow-sm h-96 overflow-y-auto custom-scrollbar">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Transaksi Terbaru</h2>
            <ul class="space-y-4">
                @forelse($recentActivities as $transaction)
                    <li class="flex justify-between items-center text-sm border-b border-gray-100 pb-3 hover:bg-gray-50 p-2 rounded-lg transition">
                        <div>
                            <p class="font-bold text-gray-900">{{ $transaction->invoice_number ?? 'TRX-'.$transaction->id }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $transaction->created_at->format('H:i') }} · 
                                <span class="text-gray-600 font-medium">{{ $transaction->payment_method ?? 'Tunai' }}</span>
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-bold text-brand-red">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                            <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold">PAID</span>
                        </div>
                    </li>
                @empty
                    <li class="text-center py-8 text-gray-400">
                        <p>Belum ada transaksi hari ini.</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
    
    <!-- Baris 2: Grafik Mingguan (Weekly Chart) - Menggunakan full-width -->
    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tren Penjualan Mingguan (8 Minggu Terakhir)</h2>
        <div style="height: 300px;">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>


    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil data dari PHP Controller
            const dataDaily = JSON.parse('{!! $chartDataDaily !!}');
            const dataWeekly = JSON.parse('{!! $chartDataWeekly !!}');
            
            // FUNGSI OPTIONS CHART (Untuk Harian dan Mingguan)
            function getChartOptions(label) {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    return label === 'Harian' ? 'Tanggal: ' + context[0].label : 'Periode: ' + context[0].label;
                                },
                                label: function(context) {
                                    if (context.parsed.y !== null) {
                                        return 'Omset: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return 'Omset: Rp 0';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Omset (Rp)' },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            title: { display: true, text: label === 'Harian' ? 'Tanggal' : 'Minggu' },
                            grid: { display: false }
                        }
                    }
                };
            }

            // 1. CHART HARIAN
            const dailyCtx = document.getElementById('dailyChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'line',
                data: dataDaily,
                options: getChartOptions('Harian'),
            });

            // 2. CHART MINGGUAN
            const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
            new Chart(weeklyCtx, {
                type: 'bar', // Menggunakan bar chart untuk mingguan lebih jelas
                data: dataWeekly,
                options: getChartOptions('Mingguan'),
            });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
@endsection
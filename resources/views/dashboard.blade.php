@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
    
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Overview Kasir</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <div class="bg-white p-6 rounded-3xl shadow-sm transform transition duration-300 hover:scale-[1.02] border-t-4 border-brand-red">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Penjualan Hari Ini</p>
                    <p class="mt-1 text-4xl font-extrabold text-gray-900">
                        Rp {{ number_format($stats['total_sales_today'], 0, ',', '.') }} 
                    </p>
                </div>
                <div class="p-3 rounded-full bg-brand-red/10 text-brand-red shadow-lg">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-brand-red flex items-center">
                <span class="mr-1">▲ 12.5%</span>
                <span class="text-gray-500 font-normal">vs. Kemarin</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm transform transition duration-300 hover:scale-[1.02] border-t-4 border-brand-gold">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Produk Aktif</p>
                    <p class="mt-1 text-4xl font-extrabold text-gray-900">
                        {{ number_format($stats['total_products']) }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-brand-gold/10 text-brand-gold shadow-lg">
                    <i data-lucide="package" class="w-8 h-8"></i>
                </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-gray-700 flex items-center">
                <span class="text-xs font-semibold text-green-500 mr-1">2 Varian Baru</span>
                <span class="text-gray-500 font-normal">minggu ini</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm transform transition duration-300 hover:scale-[1.02] border-t-4 border-gray-400">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Transaksi Terakhir</p>
                    <p class="mt-1 text-4xl font-extrabold text-gray-900">
                        @if($stats['last_transaction'])
                            {{ $stats['last_transaction']->created_at->diffForHumans() }}
                        @else
                            Baru
                        @endif
                    </p>
                </div>
                <div class="p-3 rounded-full bg-gray-400/10 text-gray-600 shadow-lg">
                    <i data-lucide="clock" class="w-8 h-8"></i>
                </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-gray-700 flex items-center">
                <span class="text-xs font-semibold text-blue-500 mr-1">3 Transaksi Pending</span>
                <span class="text-gray-500 font-normal">di sistem</span>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm h-96">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Grafik Penjualan Bulanan</h2>
            <div class="h-64 flex items-center justify-center text-gray-400 border border-dashed rounded-xl">
                [ Placeholder: Area Chart of Monthly Sales Data ]
            </div>
        </div>

        <div class="lg:col-span-1 bg-white p-6 rounded-3xl shadow-sm h-96 overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
            <ul class="space-y-4">
                @foreach($recentActivities as $activity)
                    <li class="flex justify-between items-center text-sm border-b pb-2">
                        <div>
                            <p class="font-medium text-gray-900">{{ $activity['details'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }} · 
                                @if($activity['amount'])
                                    Rp {{ number_format($activity['amount'], 0, ',', '.') }}
                                @else
                                    {{ $activity['type'] }}
                                @endif
                            </p>
                        </div>
                        
                        @php
                            $color = match($activity['status']) {
                                'SUCCESS' => 'text-green-500',
                                'PENDING' => 'text-yellow-500',
                                'ALERT' => 'text-brand-red',
                                default => 'text-gray-500'
                            };
                        @endphp
                        <span class="{{ $color }} font-semibold text-xs">{{ $activity['status'] }}</span>
                    </li>
                @endforeach
                </ul>
        </div>
    </div>
@endsection
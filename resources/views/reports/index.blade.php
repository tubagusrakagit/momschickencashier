@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')

    <!-- Header & Filter (Sudah Dipisah) -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        
        <!-- KOLOM KIRI: JUDUL -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Transaksi</h1>
            <p class="text-gray-500 text-sm">Rekapitulasi penjualan dan validasi pembayaran.</p>
        </div>
        
        <!-- KOLOM KANAN: FORM FILTER & CETAK -->
        <div class="flex flex-col md:flex-row gap-2 bg-white p-2 rounded-xl shadow-sm border border-gray-200">
            
            <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="border-gray-200 rounded-lg text-sm focus:ring-brand-red focus:border-brand-red">
                <span class="text-gray-400 hidden md:inline">-</span>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="border-gray-200 rounded-lg text-sm focus:ring-brand-red focus:border-brand-red">
                <button type="submit" class="bg-brand-red text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-700 transition">
                    Filter
                </button>
            </form>
            
            <div class="w-px h-8 bg-gray-200 mx-1 hidden md:block"></div>

            <!-- TOMBOL CETAK -->
            <a href="{{ route('reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" 
               class="flex items-center justify-center gap-2 bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Cetak PDF
            </a>
        </div>
    </div> <!-- END OF HEADER & FILTER DIV -->

    <!-- Ringkasan Cards (Summary) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Omset -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-brand-red transform hover:scale-[1.02] transition duration-300">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Total Omset</p>
            <p class="text-lg font-black text-gray-800 mt-1">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
        </div>
        
        <!-- ... Sisa Cards ... -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-brand-gold transform hover:scale-[1.02] transition duration-300">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Transaksi</p>
            <p class="text-lg font-black text-gray-800 mt-1">{{ $totalTransaksi }} <span class="text-xs font-normal text-gray-400">Nota</span></p>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-green-500 transform hover:scale-[1.02] transition duration-300">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Tunai (Cash)</p>
            <p class="text-lg font-black text-gray-800 mt-1">Rp {{ number_format($totalTunai, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-blue-500 transform hover:scale-[1.02] transition duration-300">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Non-Tunai (QRIS/Trf)</p>
            <p class="text-lg font-black text-gray-800 mt-1">Rp {{ number_format($totalNonTunai, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-bold">Waktu & Invoice</th>
                        <th class="p-4 font-bold">Kasir</th>
                        <th class="p-4 font-bold">Detail Pembayaran</th>
                        <th class="p-4 font-bold text-right">Total</th>
                        <th class="p-4 font-bold text-center">Status Validasi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
    @forelse($transactions as $trx)
        <tr class="hover:bg-gray-50 transition group">
            
            <!-- Kolom 1: Waktu & Invoice -->
            <td class="p-4">
                <p class="font-bold text-gray-800">{{ $trx->invoice_number }}</p>
                <p class="text-xs text-gray-500 flex items-center mt-1">
                    {{ $trx->created_at->format('d M Y, H:i') }}
                </p>
            </td>

            <!-- Kolom 2: Kasir -->
            <td class="p-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 border border-gray-200">
                        {{ substr($trx->user->name ?? 'A', 0, 1) }}
                    </div>
                    <span class="font-medium text-gray-700">{{ $trx->user->name ?? 'Admin' }}</span>
                </div>
            </td>

            <!-- Kolom 3: Detail Pembayaran -->
            <td class="p-4">
                <div class="flex flex-col items-start">
                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold border 
                        {{ $trx->payment_method == 'Tunai' 
                            ? 'bg-green-50 text-green-700 border-green-200' 
                            : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                        {{ $trx->payment_method }}
                    </span>
                    
                    @if($trx->payment_reference)
                        <div class="mt-1.5 flex items-start gap-1 text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md max-w-[200px]">
                            <span class="truncate" title="{{ $trx->payment_reference }}">
                                Ref: {{ Str::limit($trx->payment_reference, 25) }}
                            </span>
                        </div>
                    @endif
                </div>
            </td>

            <!-- Kolom 4: Total -->
            <td class="p-4 text-right">
                <span class="font-bold text-gray-800">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
            </td>

            <!-- Kolom 5: Validasi (SUDAH FIX) -->
            <td class="p-4 text-center">
                <form action="{{ route('reports.validate', $trx->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                        class="p-2 rounded-full transition border shadow-sm tooltip-container
                        {{ $trx->is_verified 
                            ? 'bg-green-100 text-green-600 border-green-200 hover:bg-green-200' 
                            : 'bg-gray-50 text-gray-300 border-gray-200 hover:text-blue-500 hover:border-blue-300' }}"
                        title="{{ $trx->is_verified ? 'Klik untuk membatalkan' : 'Klik untuk memvalidasi' }}">
                        
                        <!-- Ikon Centang -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $trx->is_verified ? '3' : '2' }}" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="p-10 text-center text-gray-400 flex flex-col items-center justify-center">
                <p>Tidak ada transaksi ditemukan pada rentang tanggal ini.</p>
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>

@endsection
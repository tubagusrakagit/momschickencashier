<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan Halaman Laporan
     */
    public function index(Request $request)
    {
        // 1. Ambil Input Filter Tanggal (Default: Hari Ini)
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // 2. Query Transaksi berdasarkan Rentang Tanggal
        $transactions = Transaction::with('user') // Ambil data kasir juga
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc') // Yang terbaru diatas
            ->get();

        // 3. Hitung Ringkasan Sederhana untuk Header Laporan
        $totalOmset = $transactions->sum('total_amount');
        $totalTransaksi = $transactions->count();
        $totalTunai = $transactions->where('payment_method', 'Tunai')->sum('total_amount');
        $totalNonTunai = $transactions->whereIn('payment_method', ['QRIS', 'Transfer'])->sum('total_amount');

        return view('reports.index', compact(
            'transactions', 
            'startDate', 
            'endDate',
            'totalOmset',
            'totalTransaksi',
            'totalTunai',
            'totalNonTunai'
        ));
    }

    /**
     * Mengubah status validasi transaksi (Verified <-> Unverified)
     * Fungsi inilah yang dicari oleh Laravel tadi
     */
    public function toggleValidasi($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Toggle status (Jika true jadi false, jika false jadi true)
        $transaction->is_verified = !$transaction->is_verified;
        $transaction->save();

        // Pesan notifikasi
        $statusMsg = $transaction->is_verified ? 'TERVERIFIKASI ✅' : 'BATAL VERIFIKASI ❌';
        
        return back()->with('success', "Transaksi {$transaction->invoice_number} berhasil {$statusMsg}");
    }
    public function print(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // Query yang sama persis
        $transactions = Transaction::with('user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'asc') // Urutan waktu naik (pagi ke malam)
            ->get();

        $totalOmset = $transactions->sum('total_amount');
        $totalTunai = $transactions->where('payment_method', 'Tunai')->sum('total_amount');
        $totalNonTunai = $transactions->whereIn('payment_method', ['QRIS', 'Transfer'])->sum('total_amount');

        return view('reports.print', compact(
            'transactions', 'startDate', 'endDate',
            'totalOmset', 'totalTunai', 'totalNonTunai'
        ));
    }
}
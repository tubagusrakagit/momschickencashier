<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Menampilkan Halaman Kasir (Frontend)
     */
    public function index()
    {
        // 1. Ambil produk yang stoknya ada
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        
        // 2. Ambil kategori
        $categories = Category::all();

        // 3. Generate Nomor Transaksi Unik Sementara
        // Format: TRX-20231201-0001
        $lastTransactionId = Transaction::max('id') + 1;
        $transactionCode = 'TRX-' . date('Ymd') . '-' . str_pad($lastTransactionId, 4, '0', STR_PAD_LEFT);

        return view('kasir.index', [
            'products' => $products,
            'categories' => $categories,
            'transactionCode' => $transactionCode
        ]);
    }

    /**
     * Memproses Checkout (Backend)
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'transaction_code' => 'required',
            'payment_method' => 'required|string',
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // --- HITUNG ULANG SUBTOTAL & TOTAL DI SERVER (LEBIH AMAN) ---
            $subtotal = 0;
            foreach ($request->cart as $item) {
                // Idealnya ambil harga dari DB Product lagi agar tidak dimanipulasi client
                // Tapi untuk sekarang kita pakai harga dari request cart demi kemudahan
                $subtotal += $item['price'] * $item['qty'];
            }

            $tax = $subtotal * 0.10; // Pajak 10%
            $totalAmount = $subtotal + $tax;

            // A. Simpan Data Utama Transaksi
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'invoice_number' => $request->transaction_code, // Sesuai kolom DB
                'subtotal' => $subtotal,      
                'tax_amount' => $tax,                
                'total_amount' => $totalAmount, 
                'payment_method' => $request->payment_method,
                'status' => 'paid',
            ]);

            // B. Simpan Detail & Kurangi Stok
            foreach ($request->cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price_per_unit' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Kurangi Stok Produk
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['qty']);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan!',
                'transaction_id' => $transaction->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}

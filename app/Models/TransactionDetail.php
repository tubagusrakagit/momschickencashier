<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel secara eksplisit (opsional, tapi disarankan jika nama tabel jamak)
     */
    protected $table = 'transaction_details';

    /**
     * Kolom yang boleh diisi (Mass Assignment)
     */
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price_per_unit',
        'subtotal',
    ];

    /**
     * Relasi ke Transaksi Utama
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi ke Produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

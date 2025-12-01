<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi.
     */
    protected $fillable = [
        'invoice_number',
        'user_id',
        'subtotal',
        'tax_amount',
        'total_amount',
        'payment_method',
        'status',
    ];

    /**
     * Definisikan relasi ke User (Kasir)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Definisikan relasi ke Detail Transaksi (untuk mengetahui item apa saja yang dibeli)
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
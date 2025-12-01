<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (membuat tabel transaction_details).
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key ke Tabel transactions
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            
            // Foreign Key ke Tabel products
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            $table->integer('quantity')->default(1); // Jumlah item yang dibeli
            $table->integer('price_per_unit'); // Harga produk saat transaksi terjadi (PENTING jika harga berubah)
            $table->integer('subtotal'); // subtotal item ini saja
            
            $table->timestamps();
            
            // Memastikan kombinasi transaksi dan produk adalah unik
            $table->unique(['transaction_id', 'product_id']);
        });
    }

    /**
     * Batalkan migrasi (menghapus tabel transaction_details).
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};

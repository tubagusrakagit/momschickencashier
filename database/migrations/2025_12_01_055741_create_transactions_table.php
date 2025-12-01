<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (membuat tabel transactions).
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 20)->unique(); // Nomor Invoice unik (FR-12)
            $table->foreignId('user_id')->constrained('users'); // Kasir yang melakukan transaksi
            $table->integer('subtotal'); // Total sebelum pajak
            $table->integer('tax_amount'); // Jumlah pajak (10%)
            $table->integer('total_amount'); // Total akhir yang dibayar
           $table->string('payment_method', 20);
            $table->enum('status', ['paid', 'pending', 'cancelled'])->default('paid'); // Status transaksi
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (menghapus tabel transactions).
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

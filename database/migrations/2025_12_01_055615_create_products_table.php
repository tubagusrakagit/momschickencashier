<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (membuat tabel products).
     */
   public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->string('sku', 50)->unique()->nullable();
        $table->integer('price')->unsigned();
        $table->integer('stock')->default(0);
        $table->string('image_url')->nullable();
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        
        $table->timestamps();
    });
}

    /**
     * Batalkan migrasi (menghapus tabel products).
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB; // Tambahkan ini

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        // 1. Ambil ID kategori secara acak dari database
        // Jika tabel kategori kosong, buat satu dulu biar gak error
        $categoryId = DB::table('categories')->inRandomOrder()->value('id');
        
        // Fallback: Jika database kategori beneran kosong, paksa insert 1
        if (!$categoryId) {
            $categoryId = DB::table('categories')->insertGetId(['name' => 'Umum', 'created_at' => now(), 'updated_at' => now()]);
        }

        $names = [
            'Ayam Paha Atas', 'Ayam Dada Crispy', 'Nasi Uduk Spesial', 'French Fries',
            'Es Teh Manis', 'Air Mineral', 'Paket Hemat A', 'Burger Ayam'
        ];
        
        $name = $this->faker->randomElement($names);

        return [
            'name' => $name,
            'sku' => 'SKU-' . $this->faker->unique()->randomNumber(5),
            'price' => $this->faker->numberBetween(5000, 45000),
            'stock' => $this->faker->numberBetween(10, 300),
            'image_url' => null,
            // 2. Masukkan ID Kategori (Integer), bukan String lagi
            'category_id' => $categoryId, 
        ];
    }
}
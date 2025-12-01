<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan DB
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin
        User::factory()->create([
            'name' => 'Admin Mom\'s Chicken',
            'username' => 'admin', // Tambahkan username
            'role' => 'admin',     // Tambahkan role
            'password' => Hash::make('password'),
        ]);

        // 2. SEEDING KATEGORI DULU (Manual)
        // Kita isi kategori standar restoran
        $categories = ['Makanan', 'Minuman', 'Paket Hemat', 'Snack'];
        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore([
                'name' => $cat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Baru buat Produk (karena produk butuh category_id)
        Product::factory(20)->create();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi (Mass Assignment).
     */
    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'image_url',
        'category_id', // Relasi ke tabel categories
    ];

    /**
     * Casting price dan stock sebagai integer
     */
    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * Definisikan relasi ke Kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
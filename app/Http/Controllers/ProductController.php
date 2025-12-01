<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Normalisasi string untuk pengecekan duplikat
     */
    private function normalizeString($string)
    {
        return strtoupper(str_replace(' ', '', $string));
    }

    /**
     * Cek duplikat SKU (case & space insensitive)
     */
    private function isDuplicateSku($sku, $excludeId = null)
    {
        $query = Product::whereRaw("UPPER(REPLACE(sku, ' ', '')) = ?", [$this->normalizeString($sku)]);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Product::with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate($perPage);
        $categories = Category::all();

        // Stats untuk dashboard
        $stats = [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('stock'),
            'low_stock' => Product::where('stock', '<', 10)->count(),
            'total_value' => Product::selectRaw('SUM(price * stock) as total')->value('total') ?? 0,
        ];

        // Cek jika ada request edit (untuk open modal)
        $editProduct = null;
        if ($request->has('edit')) {
            $editProduct = Product::find($request->input('edit'));
        }

        return view('produk', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'stats' => $stats,
            'editProduct' => $editProduct,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek duplikat SKU
        if ($this->isDuplicateSku($validated['sku'])) {
            return back()
                ->withInput()
                ->withErrors(['sku' => 'SKU sudah terdaftar dalam sistem.'])
                ->with('openModal', true);
        }

        // Handle upload image
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'image_url' => $imageUrl,
        ]);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $produk)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek duplikat SKU (exclude current record)
        if ($this->isDuplicateSku($validated['sku'], $produk->id)) {
            return back()
                ->withInput()
                ->withErrors(['sku' => 'SKU sudah digunakan produk lain.'])
                ->with('openModal', true)
                ->with('editId', $produk->id);
        }

        // Handle upload image baru
        $imageUrl = $produk->image_url;
        if ($request->hasFile('image')) {
            // Hapus image lama jika ada
            if ($produk->image_url) {
                Storage::disk('public')->delete($produk->image_url);
            }
            $imageUrl = $request->file('image')->store('products', 'public');
        }

        $produk->update([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'image_url' => $imageUrl,
        ]);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $produk)
    {
        // Hapus image jika ada
        if ($produk->image_url) {
            Storage::disk('public')->delete($produk->image_url);
        }

        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
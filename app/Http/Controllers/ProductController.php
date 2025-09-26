<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteType;

class ProductController extends Controller
{
    /**
     * Halaman daftar produk (index)
     */
    public function index()
    {
        // ambil semua produk beserta kategori & stok
        $produk = WasteType::with(['category', 'stock'])->get();

        return view('user.buy.index', compact('produk'));
    }

    /**
     * Halaman detail produk
     */
    public function detailBarang($id)
    {
        // ambil produk berdasarkan id
        $produk = WasteType::with(['category', 'stock'])->findOrFail($id);

        return view('user.buy.detailbarang', compact('produk'));
    }
}

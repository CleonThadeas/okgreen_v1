<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteType;
use App\Models\BuyCartItem;

class WasteController extends Controller
{
    /**
     * Halaman daftar produk
     */
    public function index()
    {
        $wastes = WasteType::with(['category', 'stock'])
            ->get()
            ->map(function ($w) {
                // Hitung total pembelian produk ini
                $w->times_bought = BuyCartItem::where('waste_type_id', $w->id)->count();
                return $w;
            });

        return view('user.buy.index', compact('wastes'));
    }

    /**
     * Halaman detail produk
     */
    public function detailBarang($id)
    {
        $produk = WasteType::with(['category', 'stock'])->findOrFail($id);

        // Hitung juga jumlah pembelian untuk produk detail
        $produk->times_bought = BuyCartItem::where('waste_type_id', $produk->id)->count();

        return view('user.buy.detailbarang', compact('produk'));
    }
}

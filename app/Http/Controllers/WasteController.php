<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteType;
use App\Models\BuyCartItem;

class WasteController extends Controller
{
    public function index()
    {
        // ambil semua tipe + kategori + stock
        $wastes = WasteType::with(['category','stock'])
            ->get()
            ->map(function($w) {
                // Hitung total pembelian produk ini dari tabel buy_cart_items
                $w->times_bought = BuyCartItem::where('waste_type_id', $w->id)->count();
                return $w;
            });

        return view('user.buy.index', compact('wastes'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\WasteType;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Ambil 6 produk terbaru dari beli barang
        $wastes = WasteType::with('category')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('user.dashboard', compact('wastes'));
    }
}

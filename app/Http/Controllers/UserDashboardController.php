<?php

namespace App\Http\Controllers;
use App\Models\WasteType;


class UserDashboardController extends Controller
{
        public function index()
    {
        // ambil 4 produk terbaru
        $wastes = WasteType::with(['category', 'stock'])->latest()->take(4)->get();

        return view('user.dashboard', compact('wastes'));
    }
}

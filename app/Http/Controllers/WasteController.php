<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\WasteType;

class WasteController extends Controller
{
    public function index()
    {
        // ambil semua tipe + kategori + stock
        $wastes = WasteType::with(['category','stock'])->get();
        return view('user.buy.index', compact('wastes'));
    }
}

<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellWasteType;
use App\Models\WasteCategory;

class SellTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    /**
     * Menampilkan daftar jenis sampah jual (sell_waste_types)
     */
    public function index()
    {
        $types = SellWasteType::with('category')
            ->orderBy('id', 'desc')
            ->get();

        return view('staff.sell_requests.types_index', compact('types'));
    }

    /**
     * Form untuk tambah jenis sampah
     */
    public function create()
    {
        $categories = WasteCategory::orderBy('category_name')->get();
        return view('staff.sell_requests.types_create', compact('categories'));
    }

    /**
     * Simpan data jenis sampah baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'type_name'         => 'required|string|max:100',
            'points_per_kg'     => 'required|numeric|min:0',
        ]);

        SellWasteType::create([
            'waste_category_id' => $request->waste_category_id,
            'type_name'         => $request->type_name,
            'points_per_kg'     => $request->points_per_kg,
        ]);

        return redirect()
            ->route('staff.sell-types.index')
            ->with('success', 'Jenis sampah jual berhasil ditambahkan.');
    }
}

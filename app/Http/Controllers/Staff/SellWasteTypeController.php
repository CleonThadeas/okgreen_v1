<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellWasteType;
use App\Models\WasteCategory;

class SellWasteTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    // list semua jenis
    public function index()
    {
        $types = SellWasteType::with('category')->orderBy('id','desc')->paginate(15);
        return view('staff.sell_types.index', compact('types'));
    }

    // form tambah jenis
    public function create()
    {
        $categories = WasteCategory::orderBy('category_name')->get();
        return view('staff.sell_types.create', compact('categories'));
    }

    // simpan jenis
    public function store(Request $r)
    {
        $r->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'type_name' => 'required|string|max:150',
            'points_per_kg' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        SellWasteType::create($r->only('waste_category_id','type_name','points_per_kg','description'));

        return redirect()->route('staff.sell-types.index')->with('success','Jenis sampah jual berhasil ditambahkan.');
    }

    // form edit
    public function edit($id)
    {
        $type = SellWasteType::findOrFail($id);
        $categories = WasteCategory::orderBy('category_name')->get();
        return view('staff.sell_types.edit', compact('type','categories'));
    }

    // update jenis
    public function update(Request $r, $id)
    {
        $r->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'type_name' => 'required|string|max:150',
            'points_per_kg' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $type = SellWasteType::findOrFail($id);
        $type->update($r->only('waste_category_id','type_name','points_per_kg','description'));

        return redirect()->route('staff.sell-types.index')->with('success','Jenis sampah jual berhasil diperbarui.');
    }

    // hapus
    public function destroy($id)
    {
        $type = SellWasteType::findOrFail($id);
        $type->delete();
        return back()->with('success','Jenis sampah jual berhasil dihapus.');
    }
}

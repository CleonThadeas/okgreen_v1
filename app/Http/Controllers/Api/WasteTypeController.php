<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WasteType;
use Illuminate\Http\Request;

class WasteTypeController extends Controller
{
    // GET all dengan relasi category
    public function index()
    {
        return response()->json(WasteType::with('category', 'stock')->get());
    }

    // POST create
    public function store(Request $request)
    {
        $request->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'type_name' => 'required|string',
            'description' => 'nullable|string',
            'price_per_unit' => 'nullable|numeric',
            'photo' => 'nullable|string',
        ]);

        $wasteType = WasteType::create($request->all());

        // otomatis bikin stok awal
        $wasteType->stock()->create([
            'available_weight' => 0
        ]);

        return response()->json($wasteType->load('stock'), 201);
    }

    // GET by id
    public function show($id)
    {
        $wasteType = WasteType::with('category', 'stock')->find($id);
        if (!$wasteType) {
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }
        return response()->json($wasteType);
    }

    // PUT update
    public function update(Request $request, $id)
    {
        $wasteType = WasteType::find($id);
        if (!$wasteType) {
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }

        $request->validate([
            'waste_category_id' => 'sometimes|exists:waste_categories,id',
            'type_name' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'price_per_unit' => 'nullable|numeric',
            'photo' => 'nullable|string',
        ]);

        $wasteType->update($request->all());
        return response()->json($wasteType->load('stock'));
    }

    // DELETE
    public function destroy($id)
    {
        $wasteType = WasteType::find($id);
        if (!$wasteType) {
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }

        $wasteType->delete();
        return response()->json(['message' => 'Tipe sampah berhasil dihapus']);
    }
}

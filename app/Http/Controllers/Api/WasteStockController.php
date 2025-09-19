<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WasteStock;
use Illuminate\Http\Request;

class WasteStockController extends Controller
{
    // GET semua data
    public function index()
    {
        return response()->json(WasteStock::with('type')->get());
    }

    // GET detail berdasarkan id
    public function show($id)
    {
        $stock = WasteStock::with('type')->find($id);
        if (!$stock) {
            return response()->json(['message' => 'Data tidak ada'], 404);
        }
        return response()->json($stock);
    }

    // POST tambah data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'waste_type_id' => 'required|exists:waste_types,id',
            'available_weight' => 'required|numeric|min:0',
        ]);

        $stock = WasteStock::create($validated);

        return response()->json($stock, 201);
    }

    // PUT update data
    public function update(Request $request, $id)
    {
        $stock = WasteStock::find($id);
        if (!$stock) {
            return response()->json(['message' => 'Data tidak ada'], 404);
        }

        $validated = $request->validate([
            'waste_type_id' => 'sometimes|exists:waste_types,id',
            'available_weight' => 'sometimes|numeric|min:0',
        ]);

        $stock->update($validated);

        return response()->json($stock);
    }

    // DELETE hapus data
    public function destroy($id)
    {
        $stock = WasteStock::find($id);
        if (!$stock) {
            return response()->json(['message' => 'Data tidak ada'], 404);
        }

        $stock->delete();

        return response()->json(['message' => 'Stock berhasil dihapus']);
    }
}

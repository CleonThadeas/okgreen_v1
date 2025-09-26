<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellWasteType;
use App\Models\WasteCategory;

class SellWasteTypeController extends Controller
{
    /**
     * GET all SellWasteType
     * Support paginate=true/false
     */
    public function index(Request $request)
    {
        $query = SellWasteType::with('category')->orderBy('id', 'desc');

        // kalau ada query ?paginate=false, ambil semua data tanpa pagination
        if ($request->has('paginate') && $request->paginate == 'false') {
            $types = $query->get();
        } else {
            $types = $query->paginate(15);
        }

        return response()->json($types);
    }

    /**
     * POST create new SellWasteType
     */
    public function store(Request $request)
    {
        $request->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'type_name'         => 'required|string|max:150',
            'points_per_kg'     => 'required|numeric|min:0',
            'description'       => 'nullable|string'
        ]);

        $type = SellWasteType::create($request->only(
            'waste_category_id',
            'type_name',
            'points_per_kg',
            'description'
        ));

        return response()->json([
            'message' => 'Jenis sampah jual berhasil ditambahkan.',
            'data'    => $type
        ], 201);
    }

    /**
     * GET by ID
     */
    public function show($id)
    {
        $type = SellWasteType::with('category')->find($id);

        if (!$type) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($type);
    }

    /**
     * PUT/PATCH update SellWasteType
     */
    public function update(Request $request, $id)
    {
        $type = SellWasteType::find($id);
        if (!$type) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'waste_category_id' => 'sometimes|exists:waste_categories,id',
            'type_name'         => 'sometimes|string|max:150',
            'points_per_kg'     => 'sometimes|numeric|min:0',
            'description'       => 'nullable|string'
        ]);

        $type->update($request->only(
            'waste_category_id',
            'type_name',
            'points_per_kg',
            'description'
        ));

        return response()->json([
            'message' => 'Jenis sampah jual berhasil diperbarui.',
            'data'    => $type
        ]);
    }

    /**
     * DELETE SellWasteType
     */
    public function destroy($id)
    {
        $type = SellWasteType::find($id);
        if (!$type) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $type->delete();
        return response()->json(['message' => 'Jenis sampah jual berhasil dihapus.']);
    }
}

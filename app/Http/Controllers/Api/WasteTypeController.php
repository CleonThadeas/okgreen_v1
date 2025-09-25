<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WasteType;
use Illuminate\Http\Request;

class WasteTypeController extends Controller
{
    public function index()
    {
        return response()->json(WasteType::with('category')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'type_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $wasteType = WasteType::create($request->all());
        return response()->json($wasteType, 201);
    }

    public function show($id)
    {
        $wasteType = WasteType::with('category')->find($id);
        if (!$wasteType) {
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }
        return response()->json($wasteType);
    }

    
}

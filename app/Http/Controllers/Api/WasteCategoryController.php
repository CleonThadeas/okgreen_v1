<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteCategory;

class WasteCategoryController extends Controller
{
    // GET all
    public function index()
    {
        return response()->json(WasteCategory::all());
    }

    // GET by id
    public function show($id)
    {
        $category = WasteCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }
        return response()->json($category);
    }

    // POST create
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100',
        ]);

        $category = WasteCategory::create([
            'category_name' => $request->category_name,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    // PUT update
    public function update(Request $request, $id)
    {
        $category = WasteCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $request->validate([
            'category_name' => 'required|string|max:100',
        ]);

        $category->update([
            'category_name' => $request->category_name,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'data' => $category
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $category = WasteCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}

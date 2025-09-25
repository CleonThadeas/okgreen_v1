<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteCategory;

class UserWasteCategoryController extends Controller
{
    // GET all categories
    public function index()
    {
        return response()->json(WasteCategory::all());
    }

    // GET category by id
    public function show($id)
    {
        $category = WasteCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }
        return response()->json($category);
    }
}

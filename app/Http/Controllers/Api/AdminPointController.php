<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PointReward;

class PointController extends Controller
{
    // List semua reward
    public function index()
    {
        $rewards = PointReward::all();

        return response()->json([
            'success' => true,
            'data' => $rewards
        ]);
    }

    // Menambahkan reward
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $reward = PointReward::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil ditambahkan',
            'data' => $reward
        ]);
    }

    // Update reward
    public function update(Request $request, $id)
    {
        $reward = PointReward::findOrFail($id);
        $reward->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil diupdate',
            'data' => $reward
        ]);
    }

    // Hapus reward
    public function destroy($id)
    {
        $reward = PointReward::findOrFail($id);
        $reward->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil dihapus'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPoint;
use App\Models\PointReward;
use App\Models\PointRedemption;

class PointController extends Controller
{
    // Cek saldo point user
    public function myPoints(Request $request)
    {
        $userPoint = UserPoint::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['points' => 0]
        );

        return response()->json([
            'success' => true,
            'data' => $userPoint
        ]);
    }

    // Tambah point ke user 
    public function addPoints(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1'
        ]);

        $userPoint = UserPoint::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['points' => 0]
        );

        $userPoint->increment('points', $request->points);

        return response()->json([
            'success' => true,
            'message' => 'Points added successfully',
            'data' => $userPoint
        ]);
    }

    // Daftar reward
    public function rewards()
    {
        $rewards = PointReward::all();

        return response()->json([
            'success' => true,
            'data' => $rewards
        ]);
    }

    // Redeem reward
    public function redeem(Request $request, $rewardId)
    {
        $user = $request->user();
        $reward = PointReward::findOrFail($rewardId);

        $userPoint = UserPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0]
        );

        if ($userPoint->points < $reward->required_points) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough points to redeem this reward'
            ], 400);
        }

        if ($reward->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Reward is out of stock'
            ], 400);
        }

        // Kurangi point & stok
        $userPoint->decrement('points', $reward->required_points);
        $reward->decrement('stock');

        // Simpan ke riwayat
        PointRedemption::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'redeemed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reward redeemed successfully',
            'reward' => $reward
        ]);
    }
}

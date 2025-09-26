<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SellWaste;
use App\Models\SellWastePhoto;
use App\Models\SellWasteType;   // pakai points_per_kg
use App\Models\WasteCategory;

class SellController extends Controller
{
    // ✅ GET semua transaksi jual user login
    public function index(Request $r)
    {
        $sells = SellWaste::where('user_id', $r->user()->id)
            ->with(['category','sellType','photos'])
            ->orderBy('created_at','desc')
            ->paginate(10);

        return response()->json($sells);
    }

    // ✅ POST jual sampah
    public function store(Request $r)
    {
        $r->validate([
            'waste_category_id'   => 'required|exists:waste_categories,id',
            'sell_waste_type_id'  => 'required|exists:sell_waste_types,id',
            'sell_method'         => 'required|in:drop_point,pickup',
            'weight'              => 'required|numeric|min:0.01',
            'photo.*'             => 'nullable|image|max:5120',
            'description'         => 'nullable|string|max:2000'
        ]);

        $user = $r->user();
        $weight = (float) $r->input('weight');

        $type = SellWasteType::findOrFail($r->sell_waste_type_id);
        $priceKg = $type->points_per_kg ?? 0;
        $total = $priceKg * $weight;

        DB::beginTransaction();
        try {
            $sell = SellWaste::create([
                'user_id'            => $user->id,
                'waste_category_id'  => $r->waste_category_id,
                'sell_waste_type_id' => $r->sell_waste_type_id,
                'weight_kg'          => $weight,
                'price_per_kg'       => $priceKg,
                'total_price'        => $total,
                'status'             => 'pending',
                'sell_method'        => $r->sell_method,
                'description'        => $r->description ?? null,
                'points_awarded'     => 0,
            ]);

            //  Upload foto
            if ($r->hasFile('photo')) {
                $files = $r->file('photo');
                $sort = 0;
                foreach ($files as $f) {
                    $path = $f->store('sell_wastes','public');
                    SellWastePhoto::create([
                        'sell_id'    => $sell->id,
                        'photo_path' => $path,
                        'sort_order' => $sort++
                    ]);
                    if ($sort === 1) {
                        $sell->photo_path = $path;
                        $sell->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Permintaan jual sampah berhasil dikirim (pending verifikasi).',
                'data'    => $sell->load(['category','sellType','photos'])
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Sell store error: '.$e->getMessage());
            return response()->json(['message' => 'Gagal mengirim permintaan jual sampah.'], 500);
        }
    }

    //  GET detail jual sampah
    public function show($id)
    {
        $sell = SellWaste::with(['category','sellType','photos'])->find($id);

        if (!$sell) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($sell);
    }

    // ✅ GET jenis sampah berdasarkan kategori
    public function getTypes($catId)
    {
        $types = SellWasteType::where('waste_category_id',$catId)
            ->orderBy('type_name')
            ->get(['id','type_name','points_per_kg']);

        return response()->json($types);
    }
}

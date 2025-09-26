<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\WasteCategory;
use App\Models\WasteType;
use App\Models\WasteStock;
use App\Models\BuyCartItem;
use App\Models\BuyTransaction;

class WasteManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff'); 
    }

    /**
     * GET /api/staff/wastes
     */
    public function index()
    {
        $wastes = WasteType::with(['category','stock'])->get();
        return response()->json($wastes);
    }

    /**
     * POST /api/staff/wastes/categories
     */
    public function storeCategory(Request $r)
    {
        $r->validate(['category_name'=>'required|string|max:100']);
        $cat = WasteCategory::create(['category_name'=>$r->category_name]);

        return response()->json([
            'message' => 'Kategori berhasil dibuat',
            'data'    => $cat
        ], 201);
    }

    /**
     * POST /api/staff/wastes
     * Buat tipe sampah baru (+upload foto opsional)
     */
    public function storeType(Request $r)
    {
        $r->validate([
            'waste_category_id'=>'required|exists:waste_categories,id',
            'type_name'=>'required|string|max:150',
            'description'=>'nullable|string',
            'price_per_unit'=>'required|numeric|min:0',
            'available_weight'=>'nullable|numeric|min:0',
            'photo'=>'nullable|file'
        ]);

        DB::beginTransaction();
        try {
            $photoPath = null;

            if ($r->hasFile('photo')) {
                $file = $r->file('photo');
                if (!$file->isValid()) {
                    return response()->json(['message'=>'Upload foto gagal (file tidak valid).'], 400);
                }
                if ($file->getSize() > 5*1024*1024) {
                    return response()->json(['message'=>'Ukuran foto melebihi 5MB.'], 400);
                }
                $allowedMimes = ['image/jpeg','image/png','image/gif','image/webp'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return response()->json(['message'=>'Format foto tidak didukung.'], 400);
                }
                $photoPath = $file->store('wastes','public');
            }

            $type = WasteType::create([
                'waste_category_id'=>$r->waste_category_id,
                'type_name'=>$r->type_name,
                'description'=>$r->description ?? null,
                'price_per_unit'=>$r->price_per_unit,
                'photo'=>$photoPath
            ]);

            WasteStock::create([
                'waste_type_id'=>$type->id,
                'available_weight'=>$r->available_weight ?? 0
            ]);

            DB::commit();
            return response()->json([
                'message'=>'Produk sampah berhasil ditambahkan',
                'data'=>$type->load('category','stock')
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API storeType error: '.$e->getMessage());
            return response()->json(['message'=>'Gagal menyimpan produk'],500);
        }
    }

    /**
     * POST /api/staff/wastes/stock
     * Tambah stok
     */
    public function addStock(Request $r)
    {
        $r->validate([
            'waste_type_id'=>'required|exists:waste_types,id',
            'quantity'=>'required|numeric|min:0.01',
            'price_per_unit'=>'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $stock = WasteStock::firstOrCreate(['waste_type_id'=>$r->waste_type_id], ['available_weight'=>0]);
            $stock->available_weight += $r->quantity;
            $stock->save();

            if ($r->filled('price_per_unit')) {
                $type = WasteType::find($r->waste_type_id);
                $type->price_per_unit = $r->price_per_unit;
                $type->save();
            }

            DB::commit();
            return response()->json([
                'message'=>'Stok berhasil ditambah',
                'data'=>$stock
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API addStock error: '.$e->getMessage());
            return response()->json(['message'=>'Gagal menambah stok'],500);
        }
    }

    /**
     * PUT /api/staff/wastes/{id}
     */
    public function updateType(Request $r, $id)
    {
        $r->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'type_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price_per_unit' => 'required|numeric|min:0',
            'adjust_type' => 'required|in:set,add',
            'stock_value' => 'required|numeric',
            'photo'=>'nullable|file'
        ]);

        DB::beginTransaction();
        try {
            $type = WasteType::findOrFail($id);
            $type->waste_category_id = $r->waste_category_id;
            $type->type_name = $r->type_name;
            $type->description = $r->description ?? null;
            $type->price_per_unit = $r->price_per_unit;

            if ($r->hasFile('photo')) {
                $file = $r->file('photo');
                if (!$file->isValid()) {
                    return response()->json(['message'=>'Upload foto gagal (file tidak valid).'],400);
                }
                if ($file->getSize() > 5*1024*1024) {
                    return response()->json(['message'=>'Ukuran foto melebihi 5MB.'],400);
                }
                $allowedMimes = ['image/jpeg','image/png','image/gif','image/webp'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return response()->json(['message'=>'Format foto tidak didukung.'],400);
                }

                if ($type->photo && Storage::disk('public')->exists($type->photo)) {
                    Storage::disk('public')->delete($type->photo);
                }
                $type->photo = $file->store('wastes','public');
            }

            $type->save();

            $stock = WasteStock::firstOrCreate(['waste_type_id'=>$type->id], ['available_weight'=>0]);
            $stockValue = (float)$r->stock_value;

            if ($r->adjust_type === 'set') {
                $stock->available_weight = max(0, $stockValue);
            } else {
                $new = $stock->available_weight + $stockValue;
                if ($new < 0) {
                    return response()->json(['message'=>'Stok tidak boleh negatif'],400);
                }
                $stock->available_weight = $new;
            }
            $stock->save();

            DB::commit();
            return response()->json([
                'message'=>'Produk berhasil diperbarui',
                'data'=>$type->load('category','stock')
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API updateType error: '.$e->getMessage());
            return response()->json(['message'=>'Gagal memperbarui produk'],500);
        }
    }

    /**
     * DELETE /api/staff/wastes/{id}
     */
    public function deleteType($id)
    {
        DB::beginTransaction();
        try {
            $type = WasteType::findOrFail($id);
            if (BuyCartItem::where('waste_type_id',$id)->exists()) {
                return response()->json([
                    'message'=>'Produk ini tidak dapat dihapus karena sudah ada transaksi terkait'
                ],400);
            }

            if ($type->photo && Storage::disk('public')->exists($type->photo)) {
                Storage::disk('public')->delete($type->photo);
            }

            WasteStock::where('waste_type_id',$id)->delete();
            $type->delete();

            DB::commit();
            return response()->json(['message'=>'Produk berhasil dihapus']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API deleteType error: '.$e->getMessage());
            return response()->json(['message'=>'Gagal menghapus produk'],500);
        }
    }

    /**
     * GET /api/staff/wastes/{id}/transactions
     */
    public function transactions($id)
    {
        $type = WasteType::with('category')->find($id);
        if (!$type) {
            return response()->json(['message'=>'Waste type tidak ditemukan'],404);
        }

        $transactionIds = BuyCartItem::where('waste_type_id',$id)->pluck('buy_transaction_id')->unique();
        $txs = BuyTransaction::whereIn('id',$transactionIds)
            ->with('items.type.category','user')
            ->orderBy('created_at','desc')
            ->paginate(20);

        return response()->json([
            'waste_type'=>$type,
            'transactions'=>$txs
        ]);
    }
}

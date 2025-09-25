<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\WasteCategory;
use App\Models\WasteType;
use App\Models\WasteStock;

class WasteManagementController extends Controller
{
    /**
     * index: list semua tipe (with category + stock)
     */
    public function index()
    {
        $wastes = WasteType::with(['category','stock'])->get();
        return view('admin.wastes.index', compact('wastes'));
    }

    /**
     * form tambah kategori
     */
    public function createCategory()
    {
        return view('admin.wastes.create_category');
    }

    public function storeCategory(Request $r)
    {
        $r->validate(['category_name'=>'required|string|max:100']);
        WasteCategory::create(['category_name'=>$r->category_name]);
        return redirect()->route('admin.wastes.index')->with('success','Kategori berhasil dibuat.');
    }

    /**
     * form tambah tipe
     */
    public function createType()
    {
        $categories = WasteCategory::all();
        return view('admin.wastes.create_type', compact('categories'));
    }

    /**
     * store tipe sampah baru (+ upload foto)
     */
    public function storeType(Request $r)
    {
        $r->validate([
            'waste_category_id'=>'required|exists:waste_categories,id',
            'type_name'=>'required|string|max:150',
            'description'=>'nullable|string',
            'price_per_unit'=>'required|numeric|min:0',
            'available_weight'=>'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:5120'

        ]);

        DB::beginTransaction();
        try {
            $photoPath = null;
            if ($r->hasFile('photo')) {
                $photoPath = $r->file('photo')->store('wastes','public');
            }

            $type = WasteType::create([
                'waste_category_id' => $r->waste_category_id,
                'type_name' => $r->type_name,
                'description' => $r->description ?? null,
                'price_per_unit' => $r->price_per_unit,
                'photo' => $photoPath
            ]);

            WasteStock::create([
                'waste_type_id' => $type->id,
                'available_weight' => $r->available_weight ?? 0
            ]);

            DB::commit();
            return redirect()->route('admin.wastes.index')->with('success','Produk sampah berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin storeType error: '.$e->getMessage());
            return back()->with('error','Gagal menyimpan produk.')->withInput();
        }
    }

    /**
     * optional: tambah stok terpisah
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
            return back()->with('success','Stok berhasil ditambah.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin addStock error: '.$e->getMessage());
            return back()->with('error','Gagal menambah stok.');
        }
    }

    /**
     * Form edit tipe (route name: admin.wastes.type.edit)
     */
    public function editType($id)
    {
        $type = WasteType::with(['category','stock'])->findOrFail($id);
        $categories = WasteCategory::orderBy('category_name')->get();
        return view('admin.wastes.edit_type', compact('type','categories'));
    }

    /**
     * Update tipe & stock + ganti foto (route name: admin.wastes.type.update)
     *
     * Body params:
     * - waste_category_id, type_name, description, price_per_unit
     * - adjust_type: 'set' or 'add'
     * - stock_value: numeric (if 'add' bisa negatif untuk mengurangi; jika 'set' harus >= 0)
     * - photo: optional image
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
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $type = WasteType::findOrFail($id);
            $type->waste_category_id = $r->waste_category_id;
            $type->type_name = $r->type_name;
            $type->description = $r->description ?? null;
            $type->price_per_unit = $r->price_per_unit;

            // handle foto baru (hapus lama jika ada)
            if ($r->hasFile('photo')) {
                if ($type->photo && Storage::disk('public')->exists($type->photo)) {
                    Storage::disk('public')->delete($type->photo);
                }
                $type->photo = $r->file('photo')->store('wastes','public');
            }
            $type->save();

            // lock stock row
            $stock = WasteStock::where('waste_type_id', $type->id)->lockForUpdate()->first();
            if (!$stock) {
                $stock = new WasteStock();
                $stock->waste_type_id = $type->id;
                $stock->available_weight = 0;
            }

            $stockValue = $r->stock_value + 0; // numeric cast

            if ($r->adjust_type === 'set') {
                if ($stockValue < 0) {
                    DB::rollBack();
                    return back()->with('error','Nilai stok tidak boleh negatif.')->withInput();
                }
                $stock->available_weight = $stockValue;
            } else {
                $new = $stock->available_weight + $stockValue;
                if ($new < 0) {
                    DB::rollBack();
                    return back()->with('error','Perubahan stok menghasilkan nilai negatif.')->withInput();
                }
                $stock->available_weight = $new;
            }

            $stock->save();

            DB::commit();
            return redirect()->route('admin.wastes.index')->with('success','Produk berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin updateType error: '.$e->getMessage());
            return back()->with('error','Gagal memperbarui produk.')->withInput();
        }
    }
}

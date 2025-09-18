<?php

namespace App\Http\Controllers\Staff;

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
    public function index()
    {
        $wastes = WasteType::with(['category','stock'])->get();
        return view('staff.wastes.index', compact('wastes'));
    }

    public function createCategory()
    {
        return view('staff.wastes.create_category');
    }

    public function storeCategory(Request $r)
    {
        $r->validate(['category_name'=>'required|string|max:100']);
        WasteCategory::create(['category_name'=>$r->category_name]);
        return redirect()->route('staff.wastes.index')->with('success','Kategori berhasil dibuat.');
    }

    public function createType()
    {
        $categories = WasteCategory::all();
        return view('staff.wastes.create_type', compact('categories'));
    }

    /**
     * store tipe sampah baru (+ upload foto)
     * Validasi file photo dilakukan manual untuk menghindari false-negative dari rule mimes
     */
    public function storeType(Request $r)
    {
        // Validasi semua kecuali foto (foto di-handle manual)
        $r->validate([
            'waste_category_id'=>'required|exists:waste_categories,id',
            'type_name'=>'required|string|max:150',
            'description'=>'nullable|string',
            'price_per_unit'=>'required|numeric|min:0',
            'available_weight'=>'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $photoPath = null;

            // Jika ada file, lakukan pemeriksaan manual
            if ($r->hasFile('photo')) {
                $file = $r->file('photo');

                // cek apakah upload valid (PHP upload error)
                if (!$file->isValid()) {
                    $err = $file->getError();
                    Log::error("File upload invalid (storeType): error code {$err}");
                    DB::rollBack();
                    return back()->with('error','Upload foto gagal (file tidak valid).')->withInput();
                }

                // cek ukuran (limit 5MB)
                $maxBytes = 5 * 1024 * 1024; // 5MB
                if ($file->getSize() > $maxBytes) {
                    Log::warning("Photo too large: {$file->getSize()} bytes");
                    DB::rollBack();
                    return back()->with('error','Ukuran foto melebihi 5MB.')->withInput();
                }

                // cek mime type & extension lebih longgar
                $mime = strtolower($file->getMimeType() ?? '');
                $ext = strtolower($file->getClientOriginalExtension() ?? '');

                // daftar mime yang kita terima (termasuk variasi umum)
                $allowedMimes = [
                    'image/jpeg','image/pjpeg','image/jpg',
                    'image/png','image/x-png',
                    'image/gif',
                    'image/webp'
                ];
                $allowedExt = ['jpg','jpeg','png','gif','webp'];

                if (!in_array($mime, $allowedMimes) || !in_array($ext, $allowedExt)) {
                    Log::error("Photo rejected: mime={$mime}, ext={$ext}");
                    DB::rollBack();
                    return back()->with('error','Format foto tidak didukung. Gunakan JPG/JPEG/PNG/GIF/WEBP.')->withInput();
                }

                // akhirnya simpan file
                // pastikan storage:link sudah dibuat jika ingin akses via storage
                $photoPath = $file->store('wastes', 'public');
                if (!$photoPath) {
                    Log::error('Gagal menyimpan file ke storage (storeType).');
                    DB::rollBack();
                    return back()->with('error','Gagal menyimpan foto.')->withInput();
                }
            } else {
                // tidak ada file di request: bukan error — foto opsional
            }

            // Simpan tipe
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
            return redirect()->route('staff.wastes.index')->with('success','Produk sampah berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Staff storeType error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error','Gagal menyimpan produk.')->withInput();
        }
    }

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
            Log::error('Staff addStock error: '.$e->getMessage());
            return back()->with('error','Gagal menambah stok.');
        }
    }

    public function editType($id)
    {
        $type = WasteType::with(['category','stock'])->findOrFail($id);
        $categories = WasteCategory::orderBy('category_name')->get();
        return view('staff.wastes.edit_type', compact('type','categories'));
    }

    public function updateType(Request $r, $id)
    {
        $r->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'type_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price_per_unit' => 'required|numeric|min:0',
            'adjust_type' => 'required|in:set,add',
            'stock_value' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $type = WasteType::findOrFail($id);
            $type->waste_category_id = $r->waste_category_id;
            $type->type_name = $r->type_name;
            $type->description = $r->description ?? null;
            $type->price_per_unit = $r->price_per_unit;

            // handle upload foto baru (manual check seperti storeType)
            if ($r->hasFile('photo')) {
                $file = $r->file('photo');
                if (!$file->isValid()) {
                    Log::error("File upload invalid (updateType): error ".$file->getError());
                    DB::rollBack();
                    return back()->with('error','Upload foto gagal (file tidak valid).')->withInput();
                }

                $maxBytes = 5 * 1024 * 1024;
                if ($file->getSize() > $maxBytes) {
                    DB::rollBack();
                    return back()->with('error','Ukuran foto melebihi 5MB.')->withInput();
                }

                $mime = strtolower($file->getMimeType() ?? '');
                $ext = strtolower($file->getClientOriginalExtension() ?? '');
                $allowedMimes = ['image/jpeg','image/pjpeg','image/jpg','image/png','image/x-png','image/gif','image/webp'];
                $allowedExt = ['jpg','jpeg','png','gif','webp'];

                if (!in_array($mime, $allowedMimes) || !in_array($ext, $allowedExt)) {
                    Log::error("Photo rejected (updateType): mime={$mime}, ext={$ext}");
                    DB::rollBack();
                    return back()->with('error','Format foto tidak didukung. Gunakan JPG/JPEG/PNG/GIF/WEBP.')->withInput();
                }

                // hapus foto lama jika ada
                if ($type->photo && Storage::disk('public')->exists($type->photo)) {
                    Storage::disk('public')->delete($type->photo);
                }

                $st = $file->store('wastes', 'public');
                if (!$st) {
                    Log::error('Gagal menyimpan file ke storage (updateType).');
                    DB::rollBack();
                    return back()->with('error','Gagal menyimpan foto.')->withInput();
                }

                $type->photo = $st;
            }

            $type->save();

            // lock the stock row
            $stock = WasteStock::where('waste_type_id', $type->id)->lockForUpdate()->first();
            if (!$stock) {
                $stock = new WasteStock();
                $stock->waste_type_id = $type->id;
                $stock->available_weight = 0;
            }

            $stockValue = (float)$r->stock_value;

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
            return redirect()->route('staff.wastes.index')->with('success','Produk berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Staff updateType error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error','Gagal memperbarui produk.')->withInput();
        }
    }

    public function transactions($id)
    {
        $type = WasteType::with('category')->findOrFail($id);

        $transactionIds = BuyCartItem::where('waste_type_id', $id)
            ->pluck('buy_transaction_id')
            ->unique()
            ->values()
            ->all();

        $txs = BuyTransaction::whereIn('id', $transactionIds)
            ->with('items.type.category', 'user')
            ->orderBy('created_at','desc')
            ->paginate(20);

        return view('staff.wastes.transactions', compact('type','txs'));
    }
    public function deleteType(Request $r, $id)
{
    DB::beginTransaction();
    try {
        $type = WasteType::findOrFail($id);

        // Jika sudah ada transaksi / item yang memakai tipe ini => tolak hapus
        $hasItems = BuyCartItem::where('waste_type_id', $id)->exists();
        if ($hasItems) {
            return back()->with('error', 'Produk ini tidak dapat dihapus karena sudah terdapat transaksi terkait. Anda bisa mengosongkan stok atau menonaktifkan produk terlebih dahulu.');
        }

        // Hapus foto dari storage publik (jika ada)
        if ($type->photo && Storage::disk('public')->exists($type->photo)) {
            Storage::disk('public')->delete($type->photo);
        }

        // Hapus stock (jika ada)
        WasteStock::where('waste_type_id', $id)->delete();

        // Hapus tipe
        $type->delete();

        DB::commit();
        return redirect()->route('staff.wastes.index')->with('success', 'Produk berhasil dihapus.');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Staff deleteType error: '.$e->getMessage(), ['id' => $id]);
        return back()->with('error', 'Gagal menghapus produk: '.$e->getMessage());
    }
}
}

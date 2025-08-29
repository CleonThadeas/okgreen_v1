<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff; // pastikan model Staff sudah ada
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * List semua staff
     */
    public function index()
    {
        $staffs = Staff::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('staffs'));
    }

    /**
     * Form create staff
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store staff baru
     */
    public function store(Request $r)
    {
        $r->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:staff,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        try {
            Staff::create([
                'name'     => $r->name,
                'email'    => $r->email,
                'password' => Hash::make($r->password),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Akun staff berhasil dibuat.');
        } catch (\Throwable $e) {
            Log::error('Admin create staff error: '.$e->getMessage());
            return back()->with('error', 'Gagal membuat akun staff.')->withInput();
        }
    }

    /**
     * Form edit staff
     */
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.users.edit', compact('staff'));
    }

    /**
     * Update staff
     */
    public function update(Request $r, $id)
    {
        $staff = Staff::findOrFail($id);

        $r->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('staff', 'email')->ignore($staff->id)],
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        try {
            $staff->name  = $r->name;
            $staff->email = $r->email;

            if ($r->filled('password')) {
                $staff->password = Hash::make($r->password);
            }

            $staff->save();

            return redirect()->route('admin.users.index')
                ->with('success', 'Akun staff berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Admin update staff error: '.$e->getMessage());
            return back()->with('error', 'Gagal memperbarui akun staff.')->withInput();
        }
    }

    /**
     * Hapus / suspend staff
     */
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);

        try {
            $staff->delete(); // kalau mau suspend bisa pakai soft delete / flag status
            return redirect()->route('admin.users.index')
                ->with('success', 'Akun staff berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Admin delete staff error: '.$e->getMessage());
            return back()->with('error', 'Gagal menghapus akun staff.');
        }
    }
}

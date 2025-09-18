<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address; 

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $address = Address::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }
}

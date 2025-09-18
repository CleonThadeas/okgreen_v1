<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellRequestController extends Controller
{
    public function index()
    {
        // sementara kosong
        $requests = []; 
        return view('staff.sell_requests.index', compact('requests'));
    }
}

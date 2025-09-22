<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // trigger event
        event(new OrderStatusUpdated($order));

        return response()->json([
            'message' => 'Order status updated & notification sent',
            'order'   => $order
        ]);
    }
}

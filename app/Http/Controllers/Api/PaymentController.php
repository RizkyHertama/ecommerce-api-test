<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function processPayment(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1'
        ]);

        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        if ($order->total_price != $request->amount) {
            return response()->json(['message' => 'Jumlah pembayaran tidak sesuai'], 400);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $request->amount,
            'status' => 'paid'
        ]);

        $order->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Pembayaran berhasil diproses',
            'data' => $payment
        ]);
    }
}

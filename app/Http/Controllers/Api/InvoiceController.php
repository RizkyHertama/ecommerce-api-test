<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function generateInvoice(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'custom_price' => 'nullable|numeric|min:0',
            'invoice_date' => 'nullable|date|before_or_equal:today'
        ]);

        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        $amount = $request->custom_price ?? $order->total_price;

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'amount' => $amount,
            'invoice_date' => $request->invoice_date ?? now()
        ]);

        return response()->json([
            'message' => 'Invoice berhasil dibuat',
            'data' => $invoice
        ]);
    }
}

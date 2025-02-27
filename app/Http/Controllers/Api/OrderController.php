<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $totalPrice = 0;
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_price' => 0,
                'status' => 'pending'
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $subtotal
                ]);

                $totalPrice += $subtotal;
            }

            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return response()->json([
                'message' => 'Order berhasil dibuat',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            Log::info("Request masuk untuk update status order ID: " . $id);

            $order = Order::find($id);
            if (!$order) {
                return response()->json([
                    'message' => 'Order tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled'
            ]);

            // Update status order
            $order->update(['status' => $validated['status']]);

            Log::info("Status berhasil diperbarui: " . $order->status);

            return response()->json([
                'message' => 'Status order berhasil diperbarui',
                'data' => $order
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error update status order: " . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui status',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}

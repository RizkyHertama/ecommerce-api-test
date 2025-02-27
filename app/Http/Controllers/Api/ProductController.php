<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'response_code' => 200,
            'message' => 'Daftar produk berhasil diambil',
            'data' => ProductResource::collection(Product::all())
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'response_code' => 404,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'response_code' => 200,
            'message' => 'Detail produk berhasil diambil',
            'data' => new ProductResource($product)
        ], 200);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'response_code' => 403,
                'message' => 'Akses ditolak, hanya admin yang dapat menambahkan produk'
            ], 403);
        }

        try {
            $product = Product::create($request->validated());

            return response()->json([
                'response_code' => 201,
                'message' => 'Produk berhasil ditambahkan',
                'data' => new ProductResource($product)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'message' => 'Gagal menambahkan produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(ProductRequest $request, $id): JsonResponse
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'response_code' => 403,
                'message' => 'Akses ditolak, hanya admin yang dapat mengupdate produk'
            ], 403);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'response_code' => 404,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        try {
            $product->update($request->validated());

            return response()->json([
                'response_code' => 200,
                'message' => 'Produk berhasil diperbarui',
                'data' => new ProductResource($product)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'message' => 'Gagal memperbarui produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'response_code' => 403,
                'message' => 'Akses ditolak, hanya admin yang dapat menghapus produk'
            ], 403);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'response_code' => 404,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        try {
            $product->delete();

            return response()->json([
                'response_code' => 200,
                'message' => 'Produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'message' => 'Gagal menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}

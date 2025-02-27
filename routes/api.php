<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApiAuthenticate;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//PRODUCTS

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});


//ORDERS & INVOICES
// Route::middleware(['auth:sanctum'])->group(function () {
Route::middleware(App\Http\Middleware\ApiAuthenticate::class)->group(function () {
    // Order Routes
    Route::post('/orders', [OrderController::class, 'store']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

    // Payment Routes
    Route::post('/payments', [PaymentController::class, 'processPayment']);

    // Invoice Routes
    Route::post('/invoices', [InvoiceController::class, 'generateInvoice']);
});



//AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(App\Http\Middleware\ApiAuthenticate::class)->group(function () {
    Route::post('/logout', function (Request $request) {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token valid, tapi user tidak ditemukan.'
            ], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    });
});

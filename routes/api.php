<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\AuthController;

// ========================================
// PUBLIC ROUTES
// ========================================

// Registration and login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Categories
Route::apiResource('categories', CategoryController::class);

// ========================================
// AUTHENTICATED ROUTES
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Get logged-in user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Product viewing: all logged-in users
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Cart routes
    Route::apiResource('cart', CartItemController::class);

    // Private test
    Route::get('/private-test', function () {
        return response()->json([
            'message' => 'Authorized'
        ]);
    });

    // ========================================
    // ADMIN-ONLY ROUTES
    // ========================================

    Route::middleware('admin')->group(function () {

        // Create, update, and delete products
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::patch('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // Delete users
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        // Admin test
        Route::get('/admin-test', function () {
            return response()->json([
                'message' => 'Welcome, Admin'
            ]);
        });

    });

});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/user', [\App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::put('/user/profile', [\App\Http\Controllers\Api\AuthController::class, 'updateProfile']);

    // Product Management (Admin only ideally, but for now authenticated)
    Route::post('/products', [\App\Http\Controllers\Api\ProductController::class, 'store']);
    Route::put('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'update']);
    Route::delete('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'destroy']);

    // Cart
    Route::get('/cart', [\App\Http\Controllers\Api\CartController::class, 'index']);
    Route::post('/cart', [\App\Http\Controllers\Api\CartController::class, 'store']);
    Route::put('/cart/{itemId}', [\App\Http\Controllers\Api\CartController::class, 'update']);
    Route::delete('/cart/{itemId}', [\App\Http\Controllers\Api\CartController::class, 'destroy']);

    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\Api\WishlistController::class, 'index']);
    Route::post('/wishlist', [\App\Http\Controllers\Api\WishlistController::class, 'store']);
    Route::delete('/wishlist/{id}', [\App\Http\Controllers\Api\WishlistController::class, 'destroy']);

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::post('/orders', [\App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);

    // Admin Order Management
    Route::put('/admin/orders/{id}/status', [\App\Http\Controllers\Api\OrderController::class, 'updateStatus']);

    // Recommendations
    Route::get('/recommendations/for-user', [\App\Http\Controllers\Api\RecommendationController::class, 'forUser']);
});

// Public Recommendations
Route::get('/recommendations/similar/{productId}', [\App\Http\Controllers\Api\RecommendationController::class, 'similarItems']);

// Interactions (Public/Auth)
Route::post('/interactions', [\App\Http\Controllers\Api\InteractionController::class, 'store']);

// Get personalized recommendations based on recent views/clicks
Route::get('/recommendations/personalized', [\App\Http\Controllers\Api\RecommendationController::class, 'personalized']);

// Get similar products based on specific product (same brand + category)
Route::get('/recommendations/similar-products/{productId}', [\App\Http\Controllers\Api\RecommendationController::class, 'similarProducts']);

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\Web\LandingController::class, 'index'])->name('landing');
Route::get('/landing', [\App\Http\Controllers\Web\LandingController::class, 'index'])->name('landing');

// Authentication Routes
Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

// Shop Routes (Customer only)
Route::middleware(['customer'])->group(function () {
    Route::get('/shop', [\App\Http\Controllers\Web\ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/product/{id}', [\App\Http\Controllers\Web\ShopController::class, 'show'])->name('shop.product');
    Route::get('/shop/recommendations', [\App\Http\Controllers\Web\ShopController::class, 'recommendations'])->name('shop.recommendations')->middleware('auth');

    // Cart & Wishlist Routes (require auth)
    Route::middleware(['auth'])->group(function () {
        Route::post('/cart/add/{productId}', [\App\Http\Controllers\Web\CartController::class, 'addToCart'])->name('cart.add');
        Route::delete('/cart/remove/{itemId}', [\App\Http\Controllers\Web\CartController::class, 'removeFromCart'])->name('cart.remove');
        Route::post('/wishlist/toggle/{productId}', [\App\Http\Controllers\Web\CartController::class, 'toggleWishlist'])->name('wishlist.toggle');
        Route::get('/cart', [\App\Http\Controllers\Web\CartController::class, 'viewCart'])->name('cart.index');
        Route::get('/checkout', [\App\Http\Controllers\Web\CartController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [\App\Http\Controllers\Web\CartController::class, 'processCheckout'])->name('checkout.submit');
        Route::get('/order/confirmation/{orderId}', [\App\Http\Controllers\Web\CartController::class, 'orderConfirmation'])->name('order.confirmation');
        Route::get('/order/history', [\App\Http\Controllers\Web\CartController::class, 'orderHistory'])->name('order.history');
    });
});

// ToyyibPay Routes (no authentication required)
Route::post('/toyyibpay/callback', [\App\Http\Controllers\Web\CartController::class, 'toyyibPayCallback'])->name('toyyibpay.callback');
Route::get('/cancel-checkout', [\App\Http\Controllers\Web\CartController::class, 'cancelCheckout'])->name('cancel.checkout');

// Test route for callback
Route::get('/test-callback', function () {
    return 'Callback route is accessible. This is a test endpoint.';
})->name('test.callback');

// Webhook testing routes
Route::get('/webhook/test', [\App\Http\Controllers\Web\WebhookTestController::class, 'testForm'])->name('webhook.test');
Route::post('/webhook/test', [\App\Http\Controllers\Web\WebhookTestController::class, 'testToyyibPay'])->name('webhook.test.submit');

// Admin Routes (Admin only)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/inventory', [\App\Http\Controllers\Admin\AdminController::class, 'inventory'])->name('inventory');
    Route::post('/inventory', [\App\Http\Controllers\Admin\AdminController::class, 'storeProduct'])->name('inventory.store');
    Route::put('/inventory/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'updateProduct'])->name('inventory.update');
    Route::delete('/inventory/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteProduct'])->name('inventory.delete');
    Route::get('/orders', [\App\Http\Controllers\Admin\AdminController::class, 'orders'])->name('orders');
    Route::put('/orders/{id}/status', [\App\Http\Controllers\Admin\AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::get('/customers', [\App\Http\Controllers\Admin\AdminController::class, 'customers'])->name('customers');
});

// Legacy routes
Route::get('/customer/product/{id}', [\App\Http\Controllers\Web\FrontController::class, 'product']);
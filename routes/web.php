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

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');

// Shop Routes (Customer only)
Route::middleware(['customer'])->group(function () {
    Route::get('/shop', [\App\Http\Controllers\Web\ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/recommendations', [\App\Http\Controllers\Web\ShopController::class, 'recommendations'])->name('shop.recommendations');
    Route::get('/shop/product/{id}', [\App\Http\Controllers\Web\ShopController::class, 'show'])->name('shop.product');

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
        Route::get('/order/{orderId}/invoice', [\App\Http\Controllers\Web\CartController::class, 'downloadInvoice'])->name('order.invoice');
        Route::put('/order/{orderId}/shipping-address', [\App\Http\Controllers\Web\CartController::class, 'updateShippingAddress'])->name('order.update-shipping');

        // Profile routes
        Route::get('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\Web\ProfileController::class, 'updatePassword'])->name('profile.password');
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
    Route::get('/inventory/{id}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'editProduct'])->name('inventory.edit');
    Route::put('/inventory/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'updateProduct'])->name('inventory.update');
    Route::delete('/inventory/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteProduct'])->name('inventory.delete');
    Route::post('/inventory/{id}/toggle-availability', [\App\Http\Controllers\Admin\AdminController::class, 'toggleAvailability'])->name('inventory.toggle');
    Route::get('/orders', [\App\Http\Controllers\Admin\AdminController::class, 'orders'])->name('orders');
    Route::put('/orders/{id}/status', [\App\Http\Controllers\Admin\AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::delete('/orders/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteOrder'])->name('orders.delete');
    Route::get('/customers', [\App\Http\Controllers\Admin\AdminController::class, 'customers'])->name('customers');
});

// Legacy routes
Route::get('/customer/product/{id}', [\App\Http\Controllers\Web\FrontController::class, 'product']);
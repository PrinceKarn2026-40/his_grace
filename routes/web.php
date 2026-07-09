<?php

use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Public shop routes
Route::get('/', [ShopController::class, 'home'])->name('home');

// Google OAuth
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// Cart (guest + auth) — view and update only for guests
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');

// Authenticated routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    // Cart add (auth only)
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');

    // Customer dashboard
    Route::get('/account', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/account/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::post('/account/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::get('/account/track', [CustomerController::class, 'trackOrder'])->name('customer.track');
    Route::post('/account/track', [CustomerController::class, 'trackOrder'])->name('customer.track.search');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/momo/{order}', [CheckoutController::class, 'momoInstructions'])->name('checkout.momo.instructions');
    Route::post('/checkout/momo/{order}/confirm', [CheckoutController::class, 'momoConfirm'])->name('checkout.momo.confirm');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Notifications
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        auth()->user()->unreadNotifications->markAsRead();
        return view('shop.notifications', compact('notifications'));
    })->name('notifications');
});

// Admin routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
        Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
        Route::post('/customers', [AdminController::class, 'storeCustomer'])->name('customers.store');
        Route::get('/customers/{user}', [AdminController::class, 'showCustomer'])->name('customers.show');
        Route::patch('/customers/{user}/toggle', [AdminController::class, 'toggleCustomerStatus'])->name('customers.toggle');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::patch('/payments/{payment}/status', [AdminController::class, 'updatePaymentStatus'])->name('payments.status');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });

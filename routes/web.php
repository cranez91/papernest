<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/acerca',[HomeController::class, 'about'])->name('about');
Route::get('/carrito', [HomeController::class, 'cart'])->name('cart');

Route::get('/articulo/{sku?}', [ProductController::class, 'show'])->name('products.detail');
Route::get('/articulos/{category?}', [ProductController::class, 'index'])->name('products.list');

Route::middleware([Authenticate::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    });

Route::middleware('web')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'store']);
    Route::patch('/cart/items/{id}', [CartController::class, 'update']);
    Route::delete('/cart/items/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']);

    Route::put('/orders/{order}', [OrderController::class, 'store'])->name('orders.store'); // idempotente
    Route::get('/orders/confirmation/{order}', [OrderController::class, 'confirmation'])->name('orders.confirmation');
});

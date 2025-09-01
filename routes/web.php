<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/contact', function () {
    return view('contact');
})
->name('contact');

Route::get('/about', function () {
    return view('about');
})
->name('about');

Route::get('/order', function () {
    return view('order');
});

Route::get('/cart', function () {
    return view('cart');
});

Route::get('/articulo/{sku?}', [ProductController::class, 'show'])->name('products.detail');
Route::get('/articulos/{category?}', [ProductController::class, 'index'])->name('products.list');

Route::middleware([Authenticate::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    });

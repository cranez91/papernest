<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/acerca',[HomeController::class, 'about'])->name('about');
Route::get('/terminos',[HomeController::class, 'termsOfService'])->name('tos');
Route::get('/privacidad',[HomeController::class, 'privacyPolicy'])->name('privacy');
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

Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create('/')
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0))
        ->add(Url::create(config('app.url') . '/acerca'))
        ->add(Url::create(config('app.url') . '/privacidad'))
        ->add(Url::create(config('app.url') . '/terminos'))
        ->add(Url::create(config('app.url') . '/articulos'))
        ->add(Url::create(config('app.url') . '/carrito'));

    return $sitemap->toResponse(request());
});
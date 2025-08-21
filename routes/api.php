<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories', fn () => \App\Models\Category::all());

Route::put('/orders/{order}', [OrderController::class, 'store']); // idempotente

Route::post('/chat/find', [ProductController::class, 'chatbotFind']);

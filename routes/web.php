<?php

use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('products', [\App\Http\Controllers\ProductsController::class, 'index'])->name('products.index');
    Route::get('products/create', [\App\Http\Controllers\ProductsController::class, 'create'])
        ->can('create', Product::class)
        ->name('products.create');
    Route::post('products', [\App\Http\Controllers\ProductsController::class, 'store'])
        ->can('create', Product::class)
        ->name('products.store');
    Route::get('products/{product}/edit', [\App\Http\Controllers\ProductsController::class, 'edit'])
        ->can('view', Product::class)
        ->name('products.edit');
    Route::put('products/{product}', [\App\Http\Controllers\ProductsController::class, 'update'])
        ->can('update', 'product')
        ->name('products.update');
    Route::delete('products/{product}', [\App\Http\Controllers\ProductsController::class, 'destroy'])
        ->can('delete', 'product')
        ->name('products.destroy');

    Route::get('api/cep/{cep}', [\App\Http\Controllers\CepController::class, 'search'])->name('api.cep.search');
    Route::post('checkout/pay', [\App\Http\Controllers\CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('checkout/success/{payment_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('checkout/failed/{payment_id}', [\App\Http\Controllers\CheckoutController::class, 'failed'])->name('checkout.failed');
    Route::get('checkout/{product}', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.form');
});

require __DIR__.'/auth.php';

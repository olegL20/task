<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('category')
    ->group(function () {
        Route::post('/', [CategoryController::class, 'create'])
            ->name('category.create');
        Route::delete('/{category}', [CategoryController::class, 'delete'])
            ->name('category.delete');
    });

Route::prefix('product')
    ->group(function () {
        Route::get('/', [ProductController::class, 'get'])->name('product.get_products');
        Route::post('/', [ProductController::class, 'create'])->name('product.create');
        Route::put('/{product}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/{product}', [ProductController::class, 'delete'])->name('product.delete');
    });

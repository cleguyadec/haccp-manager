<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LotController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LotImageController;
use App\Http\Controllers\ContainerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/products/manage', [ProductController::class, 'manage'])->name('products.manage')->middleware(['auth']);
Route::resource('products', ProductController::class)->middleware('auth');

Route::get('/containers/manage', [ContainerController::class, 'manage'])->name('containers.manage')->middleware(['auth']);
Route::post('/containers', [ContainerController::class, 'store'])->name('containers.store')->middleware(['auth']);
Route::put('/containers/{container}', [ContainerController::class, 'update'])->name('containers.update')->middleware(['auth']);
Route::delete('/containers/{container}', [ContainerController::class, 'destroy'])->name('containers.destroy')->middleware(['auth']);

// Gestion des lots
Route::get('/lots/manage', [LotController::class, 'manage'])->name('lots.manage');
Route::get('/lots/{lot}/edit', [LotController::class, 'edit'])->name('lots.edit');
Route::put('/lots/{lot}', [LotController::class, 'update'])->name('lots.update');
Route::get('/lots/create/{product}', [LotController::class, 'create'])->name('lots.create');
Route::post('/lots/{product}', [LotController::class, 'store'])->name('lots.store');
Route::delete('/lots/{lot}', [LotController::class, 'destroy'])->name('lots.destroy');

Route::get('/dashboard', [LotController::class, 'dashboard'])->name('dashboard');


// Gestion des images associées aux lots
Route::get('/lots/{lot}/images', [LotImageController::class, 'manage'])->name('lots.images.manage');
Route::post('/lots/{lot}/images', [LotImageController::class, 'store'])->name('lots.images.store');
Route::delete('/lots/images/{image}', [LotImageController::class, 'destroy'])->name('lots.images.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

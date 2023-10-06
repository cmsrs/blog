<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FrontController;
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


Route::get('/', [FrontController::class, 'index'])->name('front');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [BlogController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/blog', [BlogController::class, 'create'])->name('dashboard.blog.create');    
    Route::get('/dashboard/import', [BlogController::class, 'import'])->name('dashboard.blog.import');
    Route::post('/dashboard', [BlogController::class, 'store'])->name('dashboard.blog.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

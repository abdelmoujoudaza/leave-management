<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Leave\ListLeave;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', 'dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('leave')->group(function () {
        Route::get('/', ListLeave::class)->name('leave.list');
        // Route::get('/create', StoreLeave::class)->name('leave.create');
        // Route::get('{leave}/edit', UpdateLeave::class)->name('leave.update');
    });
});

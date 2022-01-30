<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Leave\ListLeave;
use App\Http\Livewire\Leave\StoreLeave;
use App\Http\Livewire\Allocation\ListAllocation;
use App\Http\Livewire\Allocation\StoreAllocation;
use App\Http\Livewire\Dashboard;

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
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('leave')->group(function () {
        Route::get('/', ListLeave::class)->name('leave.list');
        Route::get('/create', StoreLeave::class)->name('leave.create');
        // Route::get('{leave}/edit', UpdateLeave::class)->name('leave.update');
    });

    Route::prefix('allocation')->group(function () {
        Route::get('/', ListAllocation::class)->name('allocation.list');
        Route::get('/create', StoreAllocation::class)->name('allocation.create');
        // Route::get('{allocation}/edit', UpdateAllocation::class)->name('allocation.update');
    });
});

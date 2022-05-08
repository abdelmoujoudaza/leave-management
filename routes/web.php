<?php

use App\Http\Livewire\Dashboard;
use App\Http\Livewire\User\ListUser;
use App\Http\Livewire\User\StoreUser;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Direction\ListDirection;
use App\Http\Livewire\User\UpdateUser;
use App\Http\Livewire\Direction\StoreDirection;
use App\Http\Livewire\Station\ListStation;
use App\Http\Livewire\Station\StoreStation;

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

    Route::prefix('direction')->group(function () {
        Route::get('/', ListDirection::class)->name('direction.list');
        Route::get('/create', StoreDirection::class)->name('direction.create');
    });

    Route::group(['prefix' => 'station', 'middleware' => ['role:admin']], function () {
        Route::get('/', ListStation::class)->name('station.list');
        Route::get('/create', StoreStation::class)->name('station.create');
    });

    Route::group(['prefix' => 'student', 'middleware' => ['role:admin']], function () {
        Route::get('/', ListUser::class)->name('student.list');
        Route::get('/create', StoreUser::class)->name('student.create');
        Route::get('{user}/edit', UpdateUser::class)->name('student.update');
    });

    Route::group(['prefix' => 'driver', 'middleware' => ['role:admin']], function () {
        Route::get('/', ListUser::class)->name('driver.list');
        Route::get('/create', StoreUser::class)->name('driver.create');
        Route::get('{user}/edit', UpdateUser::class)->name('driver.update');
    });
});

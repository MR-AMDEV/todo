<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function() {
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
        Route::post('dashboard/bulk-destroy', [\App\Http\Controllers\Admin\DashboardController::class, 'bulkDestroy'])->name('dashboard.bulk-destroy');
        Route::resource('dashboard', \App\Http\Controllers\Admin\DashboardController::class);
    });

    Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'user'], function () {
        Route::resource('dashboard', \App\Http\Controllers\User\DashboardController::class);
    });
});

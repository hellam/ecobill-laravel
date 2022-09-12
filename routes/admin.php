<?php

use App\Http\Controllers\Admin;
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

Route::group(['as' => 'admin.'], function () {
    /* start authentication routes */
    Route::controller(Admin\Auth\LoginController::class)->prefix('auth')->as('auth.')->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });
    /* end authentication routes */

    Route::group(['middleware' => ['admin']], function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    });
});

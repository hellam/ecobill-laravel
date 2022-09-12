<?php

use App\Http\Controllers\User;
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


Route::group(['as' => 'user.'], function () {
    Route::post('reload-captcha', function () {
        return response()->json(['captcha' => captcha_img('math')]);
    })->name('captcha_reload');
    /* start authentication routes */
    Route::controller(User\Auth\LoginController::class)->prefix('auth')->as('auth.')->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });
    /* end authentication routes */

    Route::group(['middleware' => ['user']], function () {
        Route::get('/', [User\DashboardController::class, 'index'])->name('dashboard');
        Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
            Route::get('/', [User\Products\ProductsController::class, 'index'])->name('list');
        });
        Route::group(['prefix' => 'setup', 'as' => 'setup.'], function () {
            Route::controller(User\Setup\TaxController::class)->prefix('tax')->as('tax.')->group(function () {
                Route::get('/', 'index')->name('list');
            });
            Route::controller(User\Setup\RolesController::class)->prefix('roles')->as('roles.')->group(function () {
                Route::get('/', 'index')->name('list');
                Route::post('/', 'create')->name('add');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::put('update/{id}', 'update')->name('update');
                Route::delete('delete/{id}', 'destroy')->name('delete');
            });
        });
    });
});

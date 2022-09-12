<?php

use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;
use function App\CentralLogics\reloadCaptcha;

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
    Route::post('reload-captcha', function (){
        return response()->json(['captcha'=> captcha_img('math')]);
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
        Route::get('tax', [User\Setup\TaxController::class, 'index'])->name('tax');
        Route::get('products', [User\Products\ProductsController::class, 'index'])->name('products');
        Route::get('roles', [User\Roles\RolesController::class, 'index'])->name('roles');
    });
});

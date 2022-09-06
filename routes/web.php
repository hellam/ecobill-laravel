<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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

/* start authentication routes */
Auth::routes();
/* end authentication routes */

//Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('tax', [Controllers\Setup\TaxController::class, 'index'])->name('tax');
    Route::get('products', [Controllers\Products\ProductsController::class, 'index'])->name('products');
//});

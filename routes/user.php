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
/**
 * Use AuthController if route name == login
 */
Route::get('login', [User\Auth\LoginController::class, 'index1'])->name('login');


Route::group(['as' => 'user.'], function () {
    Route::post('reload-captcha', function () {
        return response()->json(['captcha' => captcha_img('math')]);
    })->name('captcha_reload');
    /* start authentication routes */
    Route::controller(User\Auth\LoginController::class)->prefix('auth')->as('auth.')->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'login')->middleware('throttle:5,1');
        Route::get('logout', 'logout')->name('logout');
        Route::group(['middleware' => ['user']], function () {
            Route::get('new-password', 'new_password')->name('new_password');
            Route::post('update-password', 'update_password')->name('update_password');
        });
    });
    /* end authentication routes */

    Route::group(['middleware' => ['user', 'acc.security']], function () {
        Route::get('/', [User\DashboardController::class, 'index'])->name('dashboard')->middleware('permission:100');
        Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
            Route::get('/', [User\Products\ProductsController::class, 'index'])->middleware('permission:101')->name('list');
        });

        Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
            Route::controller(User\Reports\AuditTrailController::class)->middleware('permission:601')->prefix('audit-trail')->as('audit_trail.')->group(function () {
                Route::get('/', 'index')->name('list');
                Route::get('/dt_api', 'dt_api')->name('dt_api');
            });
        });

        Route::group(['prefix' => 'setup', 'as' => 'setup.'], function () {
            Route::controller(User\Setup\TaxController::class)->prefix('tax')->as('tax.')->group(function () {
                Route::get('/', 'index')->name('list')->middleware('permission:701');
            });

            Route::controller(User\Setup\RolesController::class)->prefix('roles')->as('roles.')->group(function () {
                Route::get('/', 'index')->name('list')->middleware(['permission:702']);
                Route::get('edit/{id}', 'edit')->name('edit')->middleware('permission:7021');
                Route::put('update/{id}', 'update')->name('update')->middleware('permission:7021');
                Route::delete('delete/{id}', 'destroy')->name('delete')->middleware('permission:7022');
                Route::post('/', 'create')->name('add')->middleware('permission:703');
            });

            Route::controller(User\Setup\SecurityController::class)->prefix('security')->as('security.')->group(function () {
                Route::get('/', 'index')->name('all')->middleware('permission:704');
                Route::post('update/{type}', 'update')->name('update')->middleware('permission:7041');
            });

            Route::controller(User\Setup\MakerCheckerController::class)->prefix('maker-checker')->as('maker_checker.')->group(function () {
                Route::get('/', 'index')->name('all')->middleware('permission:705');
            });
        });
    });
});

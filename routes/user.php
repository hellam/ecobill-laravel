<?php

use App\Http\Controllers\User;
use App\Models\User as Users;
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
                Route::get('edit/{id}', 'edit')->name('edit')->middleware('permission:7021')->whereNumber('id');
                Route::put('update/{id}', 'update')->name('update')->middleware('permission:7021,'.ST_ROLE_SETUP)->whereNumber('id');
                Route::delete('delete/{id}', 'destroy')->name('delete')->middleware('permission:7022,'.ST_ROLE_SETUP)->whereNumber('id');
                Route::post('/', 'create')->name('add')->middleware('permission:703,'.ST_ROLE_SETUP);
            });

            Route::controller(User\Setup\SecurityController::class)->prefix('security')->as('security.')->group(function () {
                Route::get('/', 'index')->name('all')->middleware('permission:704');
                Route::post('update/{type}', 'update')->name('update')->middleware('permission:7041,'.ST_SECURITY_POLICY_SETUP)->whereIn('type', ['password_policy', 'general']);
            });

            Route::controller(User\Setup\MakerCheckerRulesController::class)->prefix('maker-checker-rules')->as('maker_checker_rules.')->group(function () {
                Route::get('/', 'index')->name('all')->middleware('permission:705');
                Route::post('/', 'create')->name('create')->middleware('permission:705,'.ST_MAKER_CHECKER_RULE_SETUP);
                Route::get('/dt_api', 'dt_api')->name('dt_api')->middleware('permission:705');
                Route::get('edit/{id}', 'edit')->name('edit')->middleware('permission:7051')->whereNumber('id');
                Route::put('update/{id}', 'update')->name('update')->middleware('permission:7051,'.ST_MAKER_CHECKER_RULE_SETUP)->whereNumber('id');
                Route::delete('delete/{id}', 'destroy')->name('delete')->middleware('permission:7052,'.ST_MAKER_CHECKER_RULE_SETUP)->whereNumber('id');
            });
        });

        Route::group(['prefix' => 'utils', 'as' => 'utils.'], function () {
            Route::controller(User\Utils\MakerCheckerTrxController::class)->prefix('unsupervised-data')->as('unsupervised_data.')->group(function () {
                Route::get('/', 'index')->name('all')->middleware('permission:706');
                Route::get('/dt_api', 'dt_api')->name('dt_api')->middleware('permission:706');
                Route::post('/update/{id}', 'update')->name('update')->middleware('permission:707');
            });
        });

    });
});

Route::get('/user-branches', function (){
    return response()->json([
        'user_details' => Users::with('user_branches')->where('id', 1)->first()
    ]);
});

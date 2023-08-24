<?php

use Illuminate\Http\Request;
use Modules\Terralab\Http\Controllers\Api\v1\TerralabPackageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::group([
        'prefix' => '{locale}',
        'middleware' => 'setlocale'
    ], function () {
        Route::group([
            'prefix' => 'terralab',
            'namespace' => '\Modules\Terralab\Http\Controllers\Api\v1',
            'middleware' => ['auth:api', 'checkTokenExpired']
        ], function () {
            Route::get('{user}/orders-list', 'TerralabController@index');
            Route::get('/order/{patientLaboratoryOrder}', 'TerralabController@show');
            Route::get('/get-price-list/{code?}', 'TerralabController@priceList');
            Route::post('referral/{doctor}/{patient}/{laboratory}/create-referral', 'TerralabController@store');
            Route::post('referral/{patientLaboratoryOrder}/update-referral', 'TerralabController@update');
            Route::post('referral/{patientLaboratoryOrder}/refuse-items', 'TerralabController@destroy');
            Route::get('/static', 'TerralabController@getStaticData');

            Route::group([
                'prefix' => 'packages/{package}',
            ], function () {
                Route::get('/', [TerralabPackageController::class, 'index']);
                Route::put('/', [TerralabPackageController::class, 'update']);
            });
        });

        Route::group([
            'prefix' => 'terralab/secure-external',
            'namespace' => '\Modules\Terralab\Http\Controllers\Api\v1',
        ], function () {
            Route::post('referral/set_status', 'TerralabController@setStatus');
            Route::post('referral/set_results', 'TerralabController@setResults');
            Route::post('referral/set_original_results', 'TerralabController@setOriginalResults');
        });
    });
});

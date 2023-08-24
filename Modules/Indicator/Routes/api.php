<?php

use Illuminate\Http\Request;
use Modules\Indicator\Http\Controllers\Api\v1\AnalysHistoryController;
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
            'prefix' => 'indicator',
            'namespace' => '\Modules\Indicator\Http\Controllers\Api\v1',
            'middleware' => ['auth:api', 'checkTokenExpired', 'allowOnlyDoctor']
        ], function () {
            Route::get('/analysis-groups/{catalogAnalys}', 'AnalysGroupController@index');
            Route::post('/analysis-groups/{catalogAnalys}', 'AnalysGroupController@store');
            Route::put('/analysis-groups/{catalogAnalys}', 'AnalysGroupController@update');
            Route::delete('/analysis-groups/{catalogAnalys}', 'AnalysGroupController@destroy');

            Route::get('/analysis-history/{user}', 'AnalysHistoryController@index');
            Route::get('/analysis-history/{user}/all', 'AnalysHistoryController@all');
            Route::post('/analysis-history/{user}', 'AnalysHistoryController@store');
            Route::put('/analysis-history/{user}/{analysHistory}', 'AnalysHistoryController@update');
            Route::delete('/analysis-history/{user}/{analysHistory}', 'AnalysHistoryController@destroy');

            Route::put('/indicator-history/{indicatorHistory}', 'IndicatorHistoryController@validateIndicatorHistory');
        });
        Route::group([
            'prefix' => 'indicator/patient',
            'middleware' => ['auth:api', 'checkTokenExpired', 'allowOnlyPatient']
        ], function () {
            Route::get('/',  [AnalysHistoryController::class, 'listForPatient']);
        });
    });
});

<?php


Route::group([
    'namespace' => '\TrafficIsobar\Mindbox\app\Http\Controllers',
    'prefix' => '/api/v1',
], function () {

    Route::get('test', function () {
        dd('test');
    });
    # Авторизация
    Route::post('user/auth', 'AuthController@login')->name('user.auth');

});

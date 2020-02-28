<?php


Route::group([
    'namespace' => '\TrafficIsobar\Mindbox\app\Http\Controllers',
    'prefix' => '/api/v1',
], function () {

    # Авторизация
    Route::post('user/auth', 'AuthController@userAuth')->name('user.auth');

});

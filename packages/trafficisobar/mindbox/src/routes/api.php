<?php


Route::group([
    'namespace' => '\TrafficIsobar\Mindbox\app\Http\Controllers',
    'prefix' => '/api/v1',
], function () {

    # Авторизация
    Route::post('user/auth', 'AuthController@userAuth')->name('user.auth');

    Route::group(['middleware' => 'web'], function (){
        Route::get('/action/task1', 'ActionController@taskOne')->name('action.task.one');
        Route::get('/action/task3', 'ActionController@taskThree')->name('action.task.three');
    });

});

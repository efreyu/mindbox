<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => '\TrafficIsobar\Mindbox\app\Http\Controllers',
    'prefix' => '/api/v1',
], function (Router $router) {

    # Авторизация
    $router->post('user/auth', 'AuthController@userAuth')->name('user.auth');

    $router->group(['middleware' => 'web'], function (Router $router){
        $router->get('/action/task1', 'ActionController@taskOne')->name('action.task.one');
        $router->get('/action/task3', 'ActionController@taskThree')->name('action.task.three');
    });

});

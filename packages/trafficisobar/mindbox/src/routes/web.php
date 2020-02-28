<?php

use Inertia\Inertia;

Route::group([
    'namespace' => '\TrafficIsobar\Mindbox\app\Http\Controllers',
    'middleware' => 'web'
], function () {

    Inertia::setRootView('mindbox::app');

    Route::get('/', function () {
        return Inertia::render('Home', [
            'foo' => 'bar'
        ]);
    });


    Route::get('/auth', 'AuthController@index')->name('auth.index');
    Route::post('/login', 'AuthController@login')->name('auth.login');
    Route::get('/logout', 'AuthController@logout')->name('auth.logout');



    ## Asset Routes
    Route::get('mindbox-assets', 'MindboxController@assets')->name('mindbox.assets');
});

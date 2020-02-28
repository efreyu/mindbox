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
    })->name('mindbox.home');


    Route::get('/auth', 'AuthController@index')->name('mindbox.auth.index');
    Route::post('/login', 'AuthController@login')->name('mindbox.auth.login');
    Route::get('/logout', 'AuthController@logout')->name('mindbox.auth.logout');



    ## Asset Routes
    Route::get('mindbox-assets', 'MindboxController@assets')->name('mindbox.assets');
});

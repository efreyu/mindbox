<?php

use Inertia\Inertia;

Route::group([
    'namespace' => '\TrafficIsobar\Mindbox\app\Http\Controllers',
    'middleware' => 'web'
], function () {

    Inertia::setRootView('mindbox::app');

    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'foo' => 'bar'
        ]);
    });



    ## Asset Routes
    Route::get('mindbox-assets', 'MindboxController@assets')->name('mindbox.assets');
});

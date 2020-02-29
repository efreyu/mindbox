<?php

use Inertia\Inertia;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => '\TrafficIsobar\Mindbox\app\Http\Controllers',
    'middleware' => 'web'
], function (Router $router) {

    Inertia::setRootView('mindbox::app');

    $router->get('/', function () {
        return Inertia::render('Home');
    })->name('mindbox.home');


    $router->get('/auth', 'AuthController@index')->name('mindbox.auth.index');
    $router->post('/login', 'AuthController@login')->name('mindbox.auth.login');
    $router->get('/logout', 'AuthController@logout')->name('mindbox.auth.logout');



    ## Asset Routes
    $router->get('mindbox-assets', 'MindboxController@assets')->name('mindbox.assets');
});

Route::group(['prefix' => 'tools'], function () {
    /**
     * @OA\Get(
     *     path="/tools/logs",
     *     tags={"Служебное"},
     *     summary="Логи",
     *     operationId="logs",
     *     @OA\Response(response=200, description=""),
     * )
     */
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

/**
 * @OA\Get(
 *     path="/tools/doc",
 *     tags={"Служебное"},
 *     summary="Документация",
 *     operationId="doc",
 *     @OA\Response(response=200, description=""),
 * )
 */

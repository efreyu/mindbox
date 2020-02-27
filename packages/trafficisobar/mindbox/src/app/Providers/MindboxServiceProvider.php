<?php

namespace TrafficIsobar\Mindbox\app\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class MindboxServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__.'/../../configs/mindbox.php' => config_path('mindbox.php'),
            ],
            'mindbox-config'
        );

        $this->mergeConfigFrom(__DIR__ . '/../../configs/mindbox.php', 'mindbox');
//        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'trafficisobar/minicrm');

//        $configPath = __DIR__ . '/../config/mindbox.php';
//        if (function_exists('config_path')) {
//            $publishPath = config_path('mindbox.php');
//        } else {
//            $publishPath = base_path('config/mindbox.php');
//        }
//        $this->publishes([$configPath => $publishPath], 'mindbox-config');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

//        $this->loadMigrationsFrom(__DIR__.'/path/to/migrations');
//
//        $this->loadRoutesFrom(__DIR__.'/routes.php');
//
//        $this->loadViewsFrom(__DIR__.'/path/to/views', 'courier');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('DirectCRM', function () {
            return new \TrafficIsobar\Mindbox\app\Http\Classes\DirectCRM();
        });
    }
}

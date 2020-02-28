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
        $this->mergeConfigFrom(__DIR__ . '/../../configs/mindbox.php', 'mindbox');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'mindbox');


        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->loadHelpers();
        app('router')->aliasMiddleware('web', '\Inertia\Middleware::class');

        ### Publish section

        $this->publishes(
            [
                __DIR__.'/../../configs/mindbox.php' => config_path('mindbox.php'),
            ],
            'mindbox-config'
        );
        $this->publishes(
            [
                __DIR__ . '/../../resources/assets' => resource_path('assets/vendor/mindbox')
            ],
            'mindbox-assets'
        );
        /*$this->publishes(
            [
                '/../../resources/views' => resource_path('views/vendor/trafficisobar/mindboxcore')
            ],
            'mindbox-views'
        );*/
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

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/../Http/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}

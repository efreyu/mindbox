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
        # TODO refactor this
        $this->publishes(
            [
                __DIR__.'/../../configs/mindbox.php' => config_path('mindbox.php'),
            ],
            'mindbox-config'
        );

        $this->mergeConfigFrom(__DIR__ . '/../../configs/mindbox.php', 'mindbox');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->publishes(
            [
                __DIR__ . '/../../resources/assets' => resource_path('assets/vendor/mindbox')
            ],
            'mindbox-assets'
        );
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

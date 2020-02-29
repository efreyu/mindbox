<?php

namespace TrafficIsobar\Mindbox\app\Providers;

use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Inertia\Inertia;

class MindboxServiceProvider extends ServiceProvider
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

        $this->registerPolicies();

        Auth::provider('api', function($app, array $config) {
            return $app->make(\TrafficIsobar\Mindbox\app\Providers\UserProvider::class);
        });

        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

        $this->loadHelpers();
        app('router')->aliasMiddleware('web', '\Inertia\Middleware::class');

        ### Publish section

        $this->publishes(
            [
                __DIR__.'/../../configs/mindbox.php' => config_path('mindbox.php'),
            ],
            'mindbox-config'
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
        Inertia::share('app.name', config('app.name'));
        Inertia::share('errors', function () {
            if (is_string(session()->get('errors'))) {
                return (object) [[session()->get('errors')]];
            }
            return session()->get('errors') ? session()->get('errors')->getBag('default')->getMessages() : (object) [];
        });
        Inertia::share('successMessage', function () {
            return session()->get('successMessage') ? session()->get('successMessage') : null;
        });
        Inertia::share('auth.user', function () {
            if (Auth::user()) {
                return [
                    'id' => Auth::user()->getId(),
                    'name' => Auth::user()->getUsername(),
                    'email' => Auth::user()->getEmail(),
                ];
            } else {
                return (object) [];
            }
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

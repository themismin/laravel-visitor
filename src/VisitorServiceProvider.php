<?php

namespace ThemisMin\LaravelVisitor;

use Illuminate\Support\ServiceProvider;

/**
 * Class VisitorServiceProvider.
 */
class VisitorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/migrations') => base_path('/database/migrations'),
        ],
            'migrations');

        $this->publishes([
            __DIR__.'/config/visitor.php' => config_path('visitor.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();

        $this->RegisterIp();

        $this->RegisterVisitor();

        $this->RegisterBooting();
    }

    public function RegisterVisitor()
    {
        $this->app->singleton('visitor', function ($app) {
            return new Visitor(
                $app['ThemisMin\LaravelVisitor\Storage\VisitorInterface'],
                $app['ThemisMin\LaravelVisitor\Services\Geo\GeoInterface'],
                $app['ip'],
                $app['ThemisMin\LaravelVisitor\Services\Cache\CacheInterface']

            );
        });

        $this->app->bind('ThemisMin\LaravelVisitor\Visitor', function ($app) {
            return $app['visitor'];
        });
    }

    public function RegisterIp()
    {
        $this->app->singleton('ip', function ($app) {
            return new Ip(
                $app->make('request'),
                [
                    $app->make('ThemisMin\LaravelVisitor\Services\Validation\Validator'),
                    $app->make('ThemisMin\LaravelVisitor\Services\Validation\Checker'),
                ]

            );
        });
    }

    public function registerBooting()
    {
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Visitor', 'ThemisMin\LaravelVisitor\Facades\VisitorFacade');
        });
    }

    protected function registerBindings()
    {
        $this->app->singleton(
            'ThemisMin\LaravelVisitor\Storage\VisitorInterface',
            'ThemisMin\LaravelVisitor\Storage\QbVisitorRepository'
        );

        $this->app->singleton(
            'ThemisMin\LaravelVisitor\Services\Geo\GeoInterface',
            'ThemisMin\LaravelVisitor\Services\Geo\MaxMind'
        );

        $this->app->singleton(
            'ThemisMin\LaravelVisitor\Services\Cache\CacheInterface',
            'ThemisMin\LaravelVisitor\Services\Cache\CacheClass'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['visitor'];
    }
}

<?php

namespace Webdeva\QueryCache;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class QueryCacheServiceProvider extends ServiceProvider
{
    /**
     * Register the application's services.
     *
     * @return void
     */
    public function register()
    {
        // If any services need to be registered, do it here.
    }

    /**
     * Bootstrap the application's services.
     *
     * @return void
     */
    public function boot()
    {

        $models = $this->app['config']->get('query-cache.models', []); // Optionally configure models

        foreach ($models as $model) {
            if (is_subclass_of($model, \Illuminate\Database\Eloquent\Model::class)) {
                $model::macro('cacheQuery', function () use ($model) {
                    return $model::query()->cacheQuery();
                });
            }
        }
    }
}
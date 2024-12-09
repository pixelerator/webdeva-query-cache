<?php

namespace Webdeva\QueryCache\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
trait QueryCacheable
{
    // Default cache time in seconds (1 hour)
    public $cacheFor = 3600;

    /**
     * Boot the trait for the model.
     */
    public static function bootQueryCacheable()
    {
        // Apply a global scope to the model to automatically cache queries
        static::addGlobalScope('queryCache', function (Builder $builder) {
            $builder->macro('cacheQuery', function (Builder $query) {
                // Generate a unique cache key based on the query and its bindings
                $cacheKey = md5($query->toSql() . json_encode($query->getBindings()));

                // Cache the query result or return the cached result if available
                return Cache::remember($cacheKey, now()->addSeconds($query->model->cacheFor), function () use ($query) {
                    return $query->get();
                });
            });
        });
    }

    /**
     * Flush the cache for the model based on its base cache tags.
     *
     * @param array $tags
     * @return void
     */
    public static function flushQueryCache(array $tags = [])
    {
        // Flush cache by the tag
        $cacheKey = self::getCacheKey($tags);
        Cache::tags($tags)->forget($cacheKey);
    }

    /**
     * Helper method to generate the cache key for the model based on its tags.
     *
     * @param array $tags
     * @return string
     */
    protected static function getCacheKey(array $tags = [])
    {
        return md5(implode('-', $tags));
    }
}


# Query Cache Package

## Description

`webdeva/query-cache` is a Laravel package that provides an easy way to cache Eloquent query results automatically. This package leverages Laravel's cache system to enhance performance by reducing database queries.

---

## Installation

### Step 1: Install via Composer

Run the following command to include the package in your Laravel project:

```bash
composer require webdeva/query-cache
```

### Step 2: Add the Trait to Your Models

To enable query caching for a specific model, include the `QueryCacheable` trait in your model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webdeva\QueryCache\Traits\QueryCacheable;

class Post extends Model
{
    use QueryCacheable;
}
```

---

## Usage

### Caching Queries

Once the trait is applied to a model, you can cache query results by calling the `cacheQuery` method on the query builder:

```php
$posts = Post::query()->cacheQuery();
```

The query result will be cached for **1 hour** (3600 seconds) by default.

### Flushing Cache

You can manually clear the cache for a specific query or model by calling the `flushQueryCache` method:

```php
Post::flushQueryCache(['some-cache-tags']);
```

---

## How It Works

- The `QueryCacheable` trait adds a global scope to cache query results.
- The cache key is generated using the query SQL and its bindings, ensuring unique caching for every query.
- The default cache duration is set to **3600 seconds (1 hour)**. You can modify this by overriding the `$cacheFor` property in your model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webdeva\QueryCache\Traits\QueryCacheable;

class Post extends Model
{
    use QueryCacheable;

    // Custom cache duration (e.g., 2 hours)
    public $cacheFor = 7200;
}
```

---

## Requirements

- PHP >= 7.4
- Laravel >= 10.x

---

## Advanced Example

### Query with Filters

The caching mechanism works seamlessly with filtered queries:

```php
$filteredPosts = Post::where('status', 'published')->cacheQuery();
```

### Dynamic Cache Tags

You can assign specific tags for cache management:

```php
Post::flushQueryCache(['posts', 'published']);
```

---

## Limitations

1. The package currently uses Laravel's default cache store. Ensure that your caching system (e.g., Redis, Memcached) is configured correctly.
2. Be cautious with large datasets, as caching them can increase memory usage.

---

## License

This package is open-source software licensed under the [MIT license](LICENSE).

---

## Authors

Developed and maintained by **Mohammad Khan** ([admin@webdeva.in](mailto:admin@webdeva.in)).

---

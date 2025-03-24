<?php

namespace TanNhatCMS\ModelCaching;

use Illuminate\Support\Facades\Cache;
use TanNhatCMS\ModelCaching\Contracts\BuilderInterface;
use TanNhatCMS\ModelCaching\Contracts\Cacheable;
use TanNhatCMS\ModelCaching\Exceptions\UnsupportedModelException;

/**
 * Class CacheQueryBuilder.
 *
 * Trình xây dựng truy vấn hỗ trợ cache.
 * Query builder supporting caching.
 */
class CacheQueryBuilder implements BuilderInterface
{
    protected readonly string $cacheKey;

    /**
     * @throws UnsupportedModelException
     */
    public function __construct(protected readonly string $model)
    {
        if (! in_array(Cacheable::class, class_implements($model), true)) {
            throw new UnsupportedModelException();
        }
        $this->cacheKey = $model::primaryCacheKey();
    }

    /**
     * Tìm một bản ghi theo ID.
     * Find a record by ID.
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function find(mixed $id): mixed
    {
        return Cache::remember($this->model::getCacheKey($id, $this->cacheKey), $this->model::cacheTimeout(),
            fn () => $this->model::cacheWithRelation()->where($this->cacheKey, $id)->first()
        );
    }
}

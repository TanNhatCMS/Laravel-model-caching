<?php

namespace TanNhatCMS\ModelCaching;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use TanNhatCMS\ModelCaching\Contracts\BuilderInterface;
use TanNhatCMS\ModelCaching\Contracts\Cacheable;

/**
 * Trait HasCache
 * 
 * Hỗ trợ caching cho model, giúp tăng tốc truy vấn dữ liệu.
 * Supports model caching, enhancing query performance.
 */
trait HasCache
{
    /**
     * Khởi động tính năng cache khi model được load.
     * Boot the cache feature when the model is loaded.
     */
    protected static function bootHasCache(): void
    {
        static::updating(fn($instance) => static::flushCache($instance));
        static::deleting(fn($instance) => static::flushCache($instance));
        static::created(fn() => Cache::forget(static::getCacheKeyList()));
        static::updated(fn($instance) => static::flushCache($instance));
        static::deleted(fn($instance) => static::flushCache($instance));

        if (method_exists(static::class, 'trashed')) {
            static::restored(fn($instance) => static::flushCache($instance));
        }
    }

    /**
     * Trả về khóa chính của cache.
     * Returns the primary cache key.
     *
     * @return string
     */
    public static function primaryCacheKey(): string
    {
        return 'id';
    }

    /**
     * Tạo khóa cache dựa trên ID và key tùy chọn.
     * Generate a cache key based on ID and an optional key.
     *
     * @param int|string $id
     * @param string|null $key
     * @return string
     */
    public static function getCacheKey(int|string $id, ?string $key = null): string
    {
        return md5(sprintf("%s_%s_%s", Str::slug(static::class), $key ?? static::primaryCacheKey(), $id));
    }

    /**
     * Trả về khóa cache danh sách các bản ghi.
     * Returns the cache key for the list of records.
     *
     * @return string
     */
    public static function getCacheKeyList(): string
    {
        return md5(sprintf("all_%s_cached_keys", Str::slug(static::class)));
    }

    /**
     * Trả về thời gian sống của cache (theo giây).
     * Returns the cache timeout duration (in seconds).
     *
     * @return int
     */
    public static function cacheTimeout(): int
    {
        return config('cache.ttl.id', 24 * 3600);
    }

    /**
     * Xóa cache của model khi có sự thay đổi.
     * Clears the model cache when changes occur.
     *
     * @param mixed $instance
     * @return void
     */
    protected static function flushCache($instance): void
    {
        $cacheKeys = [
            static::getCacheKey($instance->{static::primaryCacheKey()}),
            static::getCacheKeyList()
        ];
        Cache::forget($cacheKeys);
        static::flushRelatedCache($instance);
    }

    /**
     * Xóa cache liên quan của model.
     * Clears the related model cache.
     *
     * @param mixed $model
     * @return void
     */
    protected static function flushRelatedCache($model): void
    {
        foreach ($model->getTouchedRelations() as $relation) {
            foreach ([$model->{$relation}, static::getOrigin($model)->{$relation}] as $related) {
                if ($related instanceof Cacheable) {
                    Cache::forget($related::getCacheKey($related->{$related::primaryCacheKey()}));
                }
            }
        }
    }

    /**
     * Lấy bản ghi gốc của model từ bản sao.
     * Get the original record of a model from its copy.
     *
     * @param mixed $instance
     * @return static
     */
    public static function getOrigin($instance): static
    {
        return static::make($instance->getOriginal());
    }
}

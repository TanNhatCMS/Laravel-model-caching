<?php

namespace TanNhatCMS\ModelCaching\Contracts;

/**
 * Interface Cacheable
 * 
 * Định nghĩa các phương thức cần thiết cho model hỗ trợ caching.
 * Defines the required methods for cacheable models.
 */
interface Cacheable
{
    public static function primaryCacheKey(): string;

    public static function getCacheKey(int|string $id, ?string $key = null): string;

    public static function getCacheKeyList(): string;

    public static function cacheTimeout(): int;

    public function scopeCacheWithRelation($query);
}

<?php

namespace TanNhatCMS\ModelCaching\Traits;

use Illuminate\Support\Facades\Cache;
use TanNhatCMS\ModelCaching\Tests\Models\TestModel;

/**
 * Trait Cachable
 * 
 * Hỗ trợ caching cho model.
 */
trait Cachable
{
    /**
     * Lấy model từ cache hoặc database.
     *
     * @param int $id
     * @return TestModel|Cachable|null
     */
    public static function getCached(int $id): ?self
    {
        return Cache::remember("model_{$id}", 600, fn () => self::find($id));
    }

    /**
     * Xóa cache khi model thay đổi.
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        $result = parent::save($options);
        Cache::forget("model_{$this->id}");
        return $result;
    }
}

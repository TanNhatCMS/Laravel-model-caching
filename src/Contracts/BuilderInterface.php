<?php

namespace TanNhatCMS\ModelCaching\Contracts;

use Closure;
use Illuminate\Support\Collection;

/**
 * Interface BuilderInterface
 * 
 * Giao diện trình xây dựng truy vấn hỗ trợ caching.
 * Query builder interface for caching.
 */
interface BuilderInterface
{
    public function find(mixed $id): mixed;

    public function findByKey(string $key, mixed $value): mixed;

    public function get(array|string $ids): Collection;

    public function all(): Collection;

    public function when(bool $condition, Closure $callback): self;
}

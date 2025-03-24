<?php

namespace TanNhatCMS\ModelCaching\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use TanNhatCMS\ModelCaching\Traits\Cachable;

/**
 * Class TestModel.
 *
 * Model giả lập để test caching.
 */
class TestModel extends Model
{
    use Cachable;

    protected $table = 'test_models';
    protected $fillable = ['name'];
}

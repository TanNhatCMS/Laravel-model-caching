<?php

namespace TanNhatCMS\ModelCaching\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Cấu hình môi trường test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Sử dụng SQLite in-memory để test mà không cần database thật
        config(['database.default' => 'testing']);
        config(['database.connections.testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]]);

        // Tạo bảng tạm
        Schema::create('test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Xóa bảng sau khi test.
     */
    protected function tearDown(): void
    {
        Schema::dropIfExists('test_models');
        parent::tearDown();
    }
}

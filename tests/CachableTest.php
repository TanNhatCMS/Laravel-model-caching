<?php

namespace TanNhatCMS\ModelCaching\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use TanNhatCMS\ModelCaching\Tests\Models\TestModel;

class CachableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Kiểm tra model có lưu cache không.
     *
     * @test
     */
    public function it_can_cache_model(): void
    {
        $model = TestModel::create(['name' => 'Test Item']);

        // Truy vấn lần 1 (cache chưa có)
        $cachedModel = TestModel::getCached($model->id);
        $this->assertNotNull($cachedModel);
        $this->assertEquals('Test Item', $cachedModel->name);

        // Truy vấn lần 2 (lấy từ cache)
        $cachedAgain = TestModel::getCached($model->id);
        $this->assertEquals($cachedModel->id, $cachedAgain->id);
    }

    /**
     * Kiểm tra cache có bị xóa khi update không.
     *
     * @test
     */
    public function it_can_invalidate_cache_on_update(): void
    {
        $model = TestModel::create(['name' => 'Old Name']);

        // Update model
        $model->update(['name' => 'New Name']);

        // Lấy lại từ cache
        $cachedModel = TestModel::getCached($model->id);

        // Cache phải cập nhật
        $this->assertEquals('New Name', $cachedModel->name);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tạo bảng test_models để kiểm thử.
 */
return new class extends Migration
{
    /**
     * Chạy migration để tạo bảng.
     */
    public function up(): void
    {
        Schema::create('test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Rollback migration (xóa bảng).
     */
    public function down(): void
    {
        Schema::dropIfExists('test_models');
    }
};

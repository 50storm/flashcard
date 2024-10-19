<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id(); // 主キー: id
            $table->unsignedBigInteger('language_id'); // 言語ID (外部キー)
            $table->text('content'); // 内容
            $table->timestamps(); // created_at と updated_at
            // 言語テーブルとの外部キー制約を追加
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};

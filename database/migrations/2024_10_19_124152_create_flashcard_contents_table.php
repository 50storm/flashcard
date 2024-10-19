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
        Schema::create('flashcard_content', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('flashcard_id');
            $table->unsignedBigInteger('content_id');
            
            // 0: 表, 1: 裏
            $table->tinyInteger('side_type')->default(0)->comment('0: 表, 1: 裏');
            
            $table->foreign('flashcard_id')->references('id')->on('flashcards')->onDelete('cascade');
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_content');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flashcard_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flashcard_id')->constrained()->onDelete('cascade');
            $table->foreignId('front_content_id')->constrained('contents')->onDelete('cascade');
            $table->foreignId('back_content_id')->constrained('contents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flashcard_pairs');
    }
};

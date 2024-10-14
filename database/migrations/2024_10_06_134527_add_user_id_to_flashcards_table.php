<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('flashcards', function(Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');  // nullableにすることでデフォルトを許可
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashcards', function(Blueprint $table) {
            // 外部キー制約の削除
            $table->dropForeign(['user_id']);
            // カラムの削除
            $table->dropColumn('user_id');
        });
    }
};

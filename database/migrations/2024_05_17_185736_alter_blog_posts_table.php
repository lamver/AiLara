<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->boolean('telegram_post_url')->nullable();
            $table->text('telegram_add_text')->nullable();
            $table->integer('telegram_length_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('telegram_post_url');
            $table->dropColumn('telegram_add_text');
            $table->dropColumn('telegram_length_text');
        });
    }
};

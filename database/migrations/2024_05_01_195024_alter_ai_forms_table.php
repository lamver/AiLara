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
        Schema::table('ai_forms', function (Blueprint $table) {
            $table->mediumText('description_on_page')->nullable();
            $table->text('title_h1')->nullable();
            $table->text('title_h2')->nullable();
            $table->mediumText('posts_ids')->nullable();
            $table->mediumText('category_ids')->nullable();
            $table->boolean('view_posts')->nullable();
            $table->boolean('allow_comments')->nullable();
            $table->boolean('allow_indexing_results')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_forms', function (Blueprint $table) {
            $table->dropColumn('description_on_page');
            $table->dropColumn('title_h1');
            $table->dropColumn('title_h2');
            $table->dropColumn('posts_ids');
            $table->dropColumn('category_ids');
            $table->dropColumn('view_posts');
            $table->dropColumn('allow_comments');
            $table->dropColumn('allow_indexing_results');
        });
    }
};

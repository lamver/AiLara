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
        Schema::table('blog_category', function (Blueprint $table) {
            $table->string('slug', 255)->index()->nullable();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('unique_id_after_import', 255)->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_category', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('unique_id_after_import');
        });
    }
};

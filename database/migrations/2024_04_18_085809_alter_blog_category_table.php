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
        Schema::table('blog_import_job', function (Blueprint $table) {
            $table->bigInteger('author_id')->unsigned()->default(0);
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('blog_category', function (Blueprint $table) {
            $table->bigInteger('author_id')->unsigned()->default(0);
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_import_job', function (Blueprint $table) {
            $table->dropColumn('author_id');
        });

        Schema::table('blog_category', function (Blueprint $table) {
            $table->dropColumn('author_id');
        });
    }
};

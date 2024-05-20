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
            $table->integer('telegram_bot_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_import_job', function (Blueprint $table) {
            $table->dropColumn('telegram_bot_id');
        });
    }
};

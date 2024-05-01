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
            $table->mediumText('skip_url_if_entry')->nullable();
            $table->mediumText('skip_if_entries_phrases')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_import_job', function (Blueprint $table) {
            $table->dropColumn('skip_url_if_entry');
            $table->dropColumn('skip_if_entries_phrases');
        });
    }
};

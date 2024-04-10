<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('ai_search_seo_pages', 'pages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('pages', 'ai_search_seo_pages');
    }
};

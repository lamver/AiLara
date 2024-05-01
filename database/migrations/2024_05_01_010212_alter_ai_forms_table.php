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
            $table->mediumText('content_on_page')->nullable();
            $table->text('seo_title')->nullable();
            $table->mediumText('seo_description')->nullable();
            $table->mediumText('image')->nullable();
            $table->text('slug')->nullable();
            $table->mediumInteger('user_id')->nullable();
            $table->boolean('use_default')->nullable();
            $table->mediumInteger('price_per_symbol')->nullable();
            $table->mediumInteger('price_per_execute')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_forms', function (Blueprint $table) {
            $table->dropColumn('content_on_page');
            $table->dropColumn('seo_title');
            $table->dropColumn('seo_description');
            $table->dropColumn('image');
            $table->dropColumn('slug');
            $table->dropColumn('user_id');
            $table->dropColumn('use_default');
            $table->dropColumn('price_per_symbol');
            $table->dropColumn('price_per_execute');
        });
    }
};

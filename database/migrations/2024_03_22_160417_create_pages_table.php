<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add pages table
 */
return new class extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('ai_search_seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('uri', 255)->unique()->comment('Full url for page');
            $table->string('meta_title', 255)->unique()->comment('Title for search robot')->nullable();
            $table->string('meta_description', 255)->unique()->comment('Description for search robot')->nullable();
            $table->text('meta_keywords')->comment('Keyword for search robot')->nullable();
            $table->mediumText('meta_image_path')->comment('Image for search robot and good link for share')->nullable();
            $table->text('preview_title')->comment('Title for search robot and users')->nullable();
            $table->mediumText('preview_description')->comment('Description for search robot and users')->nullable();
            $table->mediumText('preview_image_path')->comment('Image for search robot and users')->nullable();
            $table->mediumText('preview_icon_svg_code')->comment('Svg for search robot and users')->nullable();
            $table->text('seo_title')->comment('Seo title for search robot and users')->nullable();
            $table->mediumText('seo_description')->comment('Seo description for search robot and users')->nullable();
            $table->longText('seo_content_page')->comment('Seo content for search robot and users')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_search_seo_pages');
    }
};

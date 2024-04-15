<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Modules\Blog\Posts;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedinteger('post_category_id')->index();
            $table->bigInteger('author_id')->unsigned()->default(0);
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->longText('content')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->comment('path to image')->nullable();
            $table->enum('status', Posts::STATUS)->default(Posts::STATUS_DEFAULT);
            $table->timestamps();
            // Add more fields as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('blog_posts');
    }
};

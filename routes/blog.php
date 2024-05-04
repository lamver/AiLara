<?php

use App\Models\Modules\Blog\Category;
use App\Settings\SettingGeneral;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Blog\PostsController;
use App\Http\Controllers\Admin\Blog\CategoryController;
use App\Http\Controllers\Admin\Blog\ImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/** Admin routes */
Route::middleware(['auth', 'verified'])->prefix(SettingGeneral::value('admin_prefix') .'/module/blog')->group(function () {
    Route::resource('posts', PostsController::class, [
/*        'except' => ['show', 'destroy'],*/
        'names' => [
            'index' => 'admin.blog.post.index',
            'create' => 'admin.blog.post.create',
            'store' => 'admin.blog.post.store',
            'edit' => 'admin.blog.post.edit',
            'update' => 'admin.blog.post.update',
            'show' => 'admin.blog.post.show',
            'destroy' => 'admin.blog.post.destroy',
        ],
    ]);

    Route::resource('category', CategoryController::class, [
        'names' => [
            'index' => 'admin.blog.category.index',
            'create' => 'admin.blog.category.create',
            'store' => 'admin.blog.category.store',
            'edit' => 'admin.blog.category.edit',
            'update' => 'admin.blog.category.update',
            'show' => 'admin.blog.category.show',
            'destroy' => 'admin.blog.category.destroy',
        ],
    ]);

    Route::resource('import', ImportController::class, [
        'names' => [
            'index' => 'admin.blog.import.index',
            'create' => 'admin.blog.import.create',
            'store' => 'admin.blog.import.store',
            'edit' => 'admin.blog.import.edit',
            'update' => 'admin.blog.import.update',
            'show' => 'admin.blog.import.show',
            'destroy' => 'admin.blog.import.destroy',
        ],
    ]);
});

/** web routes */
Route::prefix(\App\Services\Modules\Module::getWebRoutePrefix(\App\Services\Modules\Module::MODULE_BLOG))->group(function () {
    $categorySlugsRoute = Category::getFullUrlsToAllCategory();

    foreach ($categorySlugsRoute as $slug) {
        Route::get('/'.$slug, [\App\Http\Controllers\Modules\Blog\PostsController::class, 'category'])->name('blog.post.cat' . '.' . str_replace("/", ".", $slug));
        Route::get('/'.$slug . 'feed', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'rss'])->name('blog.post.cat' . '.' . str_replace("/", ".", trim($slug,'/')) . '.rss');
        Route::get('/'.$slug . '{slug}_{id}', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'view'])->name('blog.post.cat' . '.' . str_replace("/", ".", trim($slug,'/')) . '.view.post');
    }
});

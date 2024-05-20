<?php

use App\Http\Controllers\Admin\Blog\SettingsController;
use App\Models\Modules\Blog\Category;
use App\Services\Translation\Translation;
use App\Settings\SettingGeneral;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Blog\PostsController;
use App\Http\Controllers\Admin\Blog\CategoryController;
use App\Http\Controllers\Admin\Blog\ImportController;
use App\Services\Modules\Module;

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
Route::middleware(['auth', 'verified'])->prefix(Translation::checkRoutePrefix())->group(function () {
    Route::prefix(SettingGeneral::value('admin_prefix') .'/module/blog')->group(function () {
        Route::resource('posts', \App\Http\Controllers\Admin\Blog\PostsController::class, [
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

    Route::get('/settings', [SettingsController::class, 'index'])->name( 'admin.blog.settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name( 'admin.blog.settings.update');

    });
});

if (Module::isFrontModule(Module::MODULE_BLOG)) {
    /** web routes */
    Route::prefix(Module::getWebRoutePrefix(Module::MODULE_BLOG))->group(function () {

        if (Module::getWebRoutePrefix(Module::MODULE_BLOG) != '') {
            Route::get('/', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'index'])->name('blog.post.index');
        }

        $categorySlugsRoute = Category::getFullUrlsToAllCategory();

        foreach ($categorySlugsRoute as $slug) {
            Route::get('/'.$slug, [\App\Http\Controllers\Modules\Blog\PostsController::class, 'category'])->name('blog.post.cat' . '.' . str_replace("/", ".", $slug));
            Route::get('/'.$slug . 'feed', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'rss'])->name('blog.post.cat' . '.' . str_replace("/", ".", trim($slug,'/')) . '.rss');
            Route::get('/'.$slug . '{slug}_{id}', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'view'])->name('blog.post.cat' . '.' . str_replace("/", ".", trim($slug,'/')) . '.view.post');
        }
    });
}


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
Route::middleware(['auth', 'verified'])->group(function () {

    foreach (Translation::getLanguagesForRoute() as $lang) {
        Route::prefix($lang)->name('admin.' . $lang)->group(function () {
            Route::prefix(SettingGeneral::value('admin_prefix') . '/module/blog')->group(function () {
                Route::resource('posts', PostsController::class, [
                    /*        'except' => ['show', 'destroy'],*/
                    'names' => [
                        'index' => 'blog.post.index',
                        'create' => 'blog.post.create',
                        'store' => 'blog.post.store',
                        'edit' => 'blog.post.edit',
                        'update' => 'blog.post.update',
                        'show' => 'blog.post.show',
                        'destroy' => 'blog.post.destroy',
                    ],
                ]);

                Route::resource('category', CategoryController::class, [
                    'names' => [
                        'index' => 'blog.category.index',
                        'create' => 'blog.category.create',
                        'store' => 'blog.category.store',
                        'edit' => 'blog.category.edit',
                        'update' => 'blog.category.update',
                        'show' => 'blog.category.show',
                        'destroy' => 'blog.category.destroy',
                    ],
                ]);

                Route::post('category/sort', [CategoryController::class, 'sort'])->name('blog.category.sort');

                Route::resource('import', ImportController::class, [
                    'names' => [
                        'index' => 'blog.import.index',
                        'create' => 'blog.import.create',
                        'store' => 'blog.import.store',
                        'edit' => 'blog.import.edit',
                        'update' => 'blog.import.update',
                        'show' => 'blog.import.show',
                        'destroy' => 'blog.import.destroy',
                    ],
                ]);

                Route::get('/settings', [SettingsController::class, 'index'])->name('blog.settings.index');
                Route::put('/settings', [SettingsController::class, 'update'])->name('blog.settings.update');
            });
        });
    }

});

if (Module::isFrontModule(Module::MODULE_BLOG)) {
    /** web routes */
    foreach (Translation::getLanguagesForRoute() as $lang) {
        Route::prefix($lang)->group(function () use ($lang) {
            Route::prefix(Module::getWebRoutePrefix(Module::MODULE_BLOG))->group(function () use ($lang) {

                if (Module::getWebRoutePrefix(Module::MODULE_BLOG) != '') {
                    Route::get('/', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'index'])->name('blog.'.$lang.'post.index');
                }

                $categorySlugsRoute = Category::getFullUrlsToAllCategory();

                foreach ($categorySlugsRoute as $slug) {
                    Route::get('/' . $slug, [\App\Http\Controllers\Modules\Blog\PostsController::class, 'category'])->name('blog.'.$lang.'post.cat' . '.' . str_replace("/", ".", $slug));
                    Route::get('/' . $slug . 'feed', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'rss'])->name('blog.'.$lang.'post.cat' . '.' . str_replace("/", ".", trim($slug, '/')) . '.rss');
                    Route::get('/' . $slug . '{slug}_{id}', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'view'])->name('blog.'.$lang.'post.cat' . '.' . str_replace("/", ".", trim($slug, '/')) . '.view.post');
                }
            });
        });
    }
}


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
        Route::prefix($lang)->group(function () use ($lang) {
            Route::prefix(SettingGeneral::value('admin_prefix') . '/module/blog')->group(function () use ($lang) {
                Route::resource('posts', PostsController::class, [
                    /*        'except' => ['show', 'destroy'],*/
                    'names' => [
                        'index' => 'admin.' . $lang . 'blog.post.index',
                        'create' => 'admin.' . $lang . 'blog.post.create',
                        'store' => 'admin.' . $lang . 'blog.post.store',
                        'edit' => 'admin.' . $lang . 'blog.post.edit',
                        'update' => 'admin.' . $lang . 'blog.post.update',
                        'show' => 'admin.' . $lang . 'blog.post.show',
                        'destroy' => 'admin.' . $lang . 'blog.post.destroy',
                    ],
                ]);

                Route::resource('category', CategoryController::class, [
                    'names' => [
                        'index' => 'admin.' . $lang . 'blog.category.index',
                        'create' => 'admin.' . $lang . 'blog.category.create',
                        'store' => 'admin.' . $lang . 'blog.category.store',
                        'edit' => 'admin.' . $lang . 'blog.category.edit',
                        'update' => 'admin.' . $lang . 'blog.category.update',
                        'show' => 'admin.' . $lang . 'blog.category.show',
                        'destroy' => 'admin.' . $lang . 'blog.category.destroy',
                    ],
                ]);

                Route::post('category/sort', [CategoryController::class, 'sort'])->name('admin.' . $lang . 'blog.category.sort');

                Route::resource('import', ImportController::class, [
                    'names' => [
                        'index' => 'admin.' . $lang . 'blog.import.index',
                        'create' => 'admin.' . $lang . 'blog.import.create',
                        'store' => 'admin.' . $lang . 'blog.import.store',
                        'edit' => 'admin.' . $lang . 'blog.import.edit',
                        'update' => 'admin.' . $lang . 'blog.import.update',
                        'show' => 'admin.' . $lang . 'blog.import.show',
                        'destroy' => 'admin.' . $lang . 'blog.import.destroy',
                    ],
                ]);

                Route::get('/settings', [SettingsController::class, 'index'])->name('admin.' . $lang . 'blog.settings.index');
                Route::put('/settings', [SettingsController::class, 'update'])->name('admin.' . $lang . 'blog.settings.update');
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


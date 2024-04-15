<?php

use Illuminate\Support\Facades\Route;

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

$moduleName = 'blog';

//Route::get('/blog', [Controller::class, 'index'])->name($routNamePrefix . 'index');

/** Admin routes */
Route::middleware(['auth', 'verified'])->prefix(\Illuminate\Support\Facades\Config::get('ailara.admin_prefix') . '/module')->group(function () {
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
});

/** web routes */
Route::prefix(\Illuminate\Support\Facades\Config::get('modules.blog.route_prefix'))->group(function () {
    Route::get('/', [\App\Http\Controllers\Modules\Blog\PostsController::class, 'index'])->name('blog.post.index');
});

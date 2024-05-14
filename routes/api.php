<?php

use App\Http\Controllers\Ajax\AiTaskController;
use App\Http\Controllers\Api\V1\Module\Blog\ExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ajax\AiFormController;
use App\Http\Controllers\Api\V1\Module\Blog\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function () {
    Route::get('/form/config', [AiFormController::class, 'getFormConfig'])->name('ajax.ai-form.getFormConfig');
    Route::get('/form/template', [AiFormController::class, 'template'])->name('ajax.ai-form.template');
    Route::get('/form/js', [AiFormController::class, 'js'])->name('ajax.ai-form.js');
    Route::post('/task/execute', [AiFormController::class, 'execute'])->name('ajax.ai-form.execute');
    Route::get('/task/result/{id}', [AiTaskController::class, 'result'])->name('ajax.ai-form.getTask');
    Route::get('/module/blog/load', [PostController::class, 'load'])->name('api.module.blog.post.load');
    Route::get('/module/blog/post/export/{api_secret_key_rss_export}', [ExportController::class, 'export'])->name('api.module.blog.post.export');
});

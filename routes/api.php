<?php

use App\Http\Controllers\Ajax\AiTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ajax\AiFormController;

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

Route::get('/form/config', [AiFormController::class, 'getFormConfig'])->name('ajax.ai-form.getFormConfig');
Route::get('/form/template', [AiFormController::class, 'template'])->name('ajax.ai-form.template');
Route::get('/form/js', [AiFormController::class, 'js'])->name('ajax.ai-form.js');
Route::post('/task/execute', [AiFormController::class, 'execute'])->name('ajax.ai-form.execute');
Route::get('/task/get_task', [AiTaskController::class, 'getTask'])->name('ajax.ai-form.getTask');

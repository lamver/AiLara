<?php

use Illuminate\Support\Facades\Route;
use App\Settings\SettingGeneral;

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
Route::middleware(['auth', 'verified'])->prefix(app(SettingGeneral::class)->admin_prefix . '/module/ai-forms')->group(function () {
    Route::resource('ai-form', \App\Http\Controllers\Admin\AiForms\AiFormController::class, [
        'names' => [
            'index' => 'admin.module.ai-form.index',
            'create' => 'admin.module.ai-form.create',
            'store' => 'admin.module.ai-form.store',
            'edit' => 'admin.module.ai-form.edit',
            'update' => 'admin.module.ai-form.update',
            'show' => 'admin.module.ai-form.show',
            'destroy' => 'admin.module.ai-form.destroy',
        ],
    ]);
});

/** web routes */

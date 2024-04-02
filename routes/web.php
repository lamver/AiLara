<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\Integration\AiSearchController;
use App\Http\Controllers\AiSearch\ControlPanel\SeoPages;
use App\Http\Controllers\AiSearch\TaskController;


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

Route::get('/', [Controller::class, 'index'])->name('index');
Route::get('/auth/btn.html', [\App\Http\Controllers\Ajax\UserStateController::class, 'authBtn'])
     ->name('ajax.auth-btn');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', [MainController::class, 'index'])->name('admin.index');
    Route::get('/admin/configuration', [MainController::class, 'configuration'])->name('admin.configuration');
    Route::post('/admin/configuration', [MainController::class, 'configuration'])->name('admin.configuration.save');

    Route::get('/admin/ais/common-data', [AiSearchController::class, 'commonData'])->name('admin.ais.commonData');

    Route::get('/admin/pages', [SeoPages::class, 'seoPagesList'])->name('admin.ais.pages');
    Route::get('/admin/page-edit/{id}', [SeoPages::class, 'seoPageEdit'])->name('admin.ais.page.edit');
    Route::post('/admin/page-save/{id}', [SeoPages::class, 'seoPageSave'])->name('admin.ais.page.save');

    Route::get('/admin/ais/ai-forms', [AiSearchController::class, 'aiForms'])
        ->name('admin.ais.aiForms');
    Route::get('/admin/ais/ai-forms/new-form', [AiSearchController::class, 'newForm'])
        ->name('admin.ais.aiForms.newForm');
    Route::post('/admin/ais/ai-forms/new-form-create', [AiSearchController::class, 'newFormCreate'])
        ->name('admin.ais.aiForms.newFormCreate');
    Route::any('/admin/ais/ai-forms/form-edit/{formId}', [AiSearchController::class, 'formEdit'])
         ->name('admin.ais.aiForms.formEdit')
         ->where('formId', '[0-9]+');
    Route::get('/admin/ais/ai-forms/form-delete/{formId}', [AiSearchController::class, 'formDelete'])
         ->name('admin.ais.aiForms.formDelete')
         ->where('formId', '[0-9]+');
});

require __DIR__.'/auth.php';

Route::get('/{slug}/{id}', [TaskController::class, 'view'])
     ->name('task.view')
     ->where('id', '[0-9]+');

Route::get('/', [Controller::class, 'index'])->name('index');

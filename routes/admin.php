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

Route::middleware(['auth', 'verified'])->prefix(\Illuminate\Support\Facades\Config::get('ailara.admin_prefix'))->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('admin.index');
    Route::get('/configuration', [MainController::class, 'configuration'])->name('admin.configuration');
    Route::post('/configuration', [MainController::class, 'configuration'])->name('admin.configuration.save');

    Route::get('/ais/common-data', [AiSearchController::class, 'commonData'])->name('admin.ais.commonData');

    Route::get('/pages', [SeoPages::class, 'seoPagesList'])->name('admin.ais.pages');
    Route::get('/page-edit/{id}', [SeoPages::class, 'seoPageEdit'])->name('admin.ais.page.edit');
    Route::post('/page-save/{id}', [SeoPages::class, 'seoPageSave'])->name('admin.ais.page.save');

    Route::get('/ais/ai-forms', [AiSearchController::class, 'aiForms'])
        ->name('admin.ais.aiForms');
    Route::get('/ais/ai-forms/new-form', [AiSearchController::class, 'newForm'])
        ->name('admin.ais.aiForms.newForm');
    Route::post('/ais/ai-forms/new-form-create', [AiSearchController::class, 'newFormCreate'])
        ->name('admin.ais.aiForms.newFormCreate');
    Route::any('/ais/ai-forms/form-edit/{formId}', [AiSearchController::class, 'formEdit'])
        ->name('admin.ais.aiForms.formEdit')
        ->where('formId', '[0-9]+');
    Route::get('/ais/ai-forms/form-delete/{formId}', [AiSearchController::class, 'formDelete'])
        ->name('admin.ais.aiForms.formDelete')
        ->where('formId', '[0-9]+');
    Route::get('/update', [\App\Http\Controllers\Admin\UpdateController::class, 'index'])->name('admin.update');
});
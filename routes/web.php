<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use \App\Http\Controllers\Admin\MainController;
use \App\Http\Controllers\Admin\Integration\AiSearchController;

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


Route::middleware(['auth', 'verified', 'rbac:admin'])->group(function () {
    Route::get('/admin', [\App\Http\Controllers\Admin\MainController::class, 'index'])->name('admin.index');
    Route::get('/admin/configuration', [\App\Http\Controllers\Admin\MainController::class, 'configuration'])->name('admin.configuration');
    Route::post('/admin/configuration', [\App\Http\Controllers\Admin\MainController::class, 'configuration'])->name('admin.configuration.save');
    Route::get('/admin/ais/common-data', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'commonData'])->name('admin.ais.commonData');
    Route::get('/admin/ais/pages', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'pages'])->name('admin.ais.pages');

    Route::get('/admin/ais/ai-forms', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'aiForms'])->name('admin.ais.aiForms');
    Route::get('/admin/ais/ai-forms/new-form', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'newForm'])->name('admin.ais.aiForms.newForm');
    Route::post('/admin/ais/ai-forms/new-form-create', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'newFormCreate'])->name('admin.ais.aiForms.newFormCreate');
    Route::any('/admin/ais/ai-forms/form-edit/{formId}', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'formEdit'])
         ->name('admin.ais.aiForms.formEdit')
         ->where('formId', '[0-9]+');
    Route::get('/admin/ais/ai-forms/form-delete/{formId}', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'formDelete'])
         ->name('admin.ais.aiForms.formDelete')
         ->where('formId', '[0-9]+');
});

require __DIR__.'/auth.php';

Route::get('/{slug}/{id}', [\App\Http\Controllers\AiSearch\TaskController::class, 'view'])
     ->name('task.view')
     ->where('id', '[0-9]+');

Route::get('/', [Controller::class, 'index'])->name('index');

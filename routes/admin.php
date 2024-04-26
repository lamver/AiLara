<?php

use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\Integration\AiSearchController;
use App\Http\Controllers\AiSearch\ControlPanel\SeoPages;

use App\Services\Translation\Translation;


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

Route::middleware(['auth', 'verified'])->prefix(Translation::checkRoutePrefix())->group(function () {
    Route::prefix(Config::get('ailara.admin_prefix'))->group(function () {
        Route::get('/', [MainController::class, 'index'])->name('admin.index');
        Route::get('/configuration', [MainController::class, 'configuration'])->name('admin.configuration');
        Route::post('/configuration', [MainController::class, 'configuration'])->name('admin.configuration.save');
        Route::get('/configuration/robots_txt', [MainController::class, 'robotsTxt'])->name('admin.configuration.robots_txt');
        Route::post('/configuration/robots_txt', [MainController::class, 'robotsTxt'])->name('admin.configuration.robots_txt.save');

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
        Route::get('/logs', [\App\Http\Controllers\Admin\LogsController::class, 'index'])->name('admin.logs');

        Route::get('/setLang/{locale}', function (string $locale) {
            app()->setLocale($locale);
            session()->put('locale', $locale);
        })->name('setLang');

        Route::resource('user', UserController::class, [
            'names' => [
                'index' => 'admin.user.index',
                'create' => 'admin.user.create',
                'store' => 'admin.user.store',
                'edit' => 'admin.user.edit',
                'update' => 'admin.user.update',
                'show' => 'admin.user.show',
                'destroy' => 'admin.user.destroy',
            ],
        ]);

        Route::post('/log-as-user', [UserController::class, 'logInAsUser'])->name('admin.logInAsUser');
        
    });
});

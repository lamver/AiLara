<?php

use App\Http\Controllers\Admin\AiQueryController;
use App\Http\Controllers\Admin\Integration\AdminTelegramBotController;
use App\Http\Controllers\Admin\Integration\AiSearchController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AiSearch\ControlPanel\SeoPages;
use App\Services\Translation\Translation;
use App\Settings\SettingGeneral;
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

Route::middleware(['auth', 'verified', 'rbac:admin'])->prefix(Translation::checkRoutePrefix())->group(function () {

    Route::prefix(app(SettingGeneral::class)->admin_prefix)->group(function () {
        Route::get('/', [MainController::class, 'index'])->name('admin.index');
        Route::get('/configuration', [MainController::class, 'configuration'])->name('admin.configuration');
        Route::post('/configuration', [MainController::class, 'configuration'])->name('admin.configuration.save');
        Route::get('/configuration/robots_txt', [MainController::class, 'robotsTxt'])->name('admin.configuration.robots_txt');
        Route::post('/configuration/robots_txt', [MainController::class, 'robotsTxt'])->name('admin.configuration.robots_txt.save');

        Route::get('/ais/common-data', [AiSearchController::class, 'commonData'])->name('admin.ais.commonData');

        Route::get('/routes', [SeoPages::class, 'routeList'])->name('admin.ais.pages');
        Route::get('/page-edit/{id}', [SeoPages::class, 'seoPageEdit'])->name('admin.ais.page.edit');
        Route::post('/page-save/{id}', [SeoPages::class, 'seoPageSave'])->name('admin.ais.page.save');

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

        Route::resource("/telegram-bots", AdminTelegramBotController::class)->except('show');

        Route::post('/log-as-user', [UserController::class, 'logInAsUser'])->name('admin.logInAsUser');

        /** Ai routes */
        Route::post('/create-ai-task', [AiQueryController::class, 'createTask'])->name('admin.createAiTask');
        Route::post('/get-ai-task', [AiQueryController::class, 'getTaskByTaskId'])->name('admin.getAiTask');

    });
});

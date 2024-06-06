<?php

use App\Http\Controllers\Admin\AiQueryController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\Integration\AdminTelegramBotController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiSearch\ControlPanel\SeoPages;
use App\Services\Translation\Translation;
use App\Settings\SettingGeneral;
use App\Http\Controllers\Admin\ModuleMainController;
use Wnikk\LaravelAccessUi\Http\Controllers\AccessUiController;
use Wnikk\LaravelAccessUi\Http\Controllers\RulesController;
use Wnikk\LaravelAccessUi\Http\Controllers\OwnersController;
use Wnikk\LaravelAccessUi\Http\Controllers\InheritController;
use Wnikk\LaravelAccessUi\Http\Controllers\PermissionController;


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

Route::middleware(['auth', 'verified', 'rbac:admin'])->group(function () {

    foreach (Translation::getLanguagesForRoute() as $lang) {

        Route::prefix($lang)->name('admin.' . $lang)->group(function () {
            Route::prefix(SettingGeneral::value('admin_prefix'))->group(function () {
                Route::get('/', [MainController::class, 'index'])->name('index');
                Route::get('/configuration', [MainController::class, 'configuration'])->name('configuration');
                Route::post('/configuration', [MainController::class, 'configuration'])->name('configuration.save');
                Route::get('/configuration/robots_txt', [MainController::class, 'robotsTxt'])->name('configuration.robots_txt');
                Route::post('/configuration/robots_txt', [MainController::class, 'robotsTxt'])->name('configuration.robots_txt.save');

                Route::get('/modules/common-configuration', [ModuleMainController::class, 'commonConfiguration'])->name('modules.main.config');
                Route::post('/modules/common-configuration', [ModuleMainController::class, 'commonConfigurationSave'])->name('modules.main.config.save');

                Route::get('/routes', [SeoPages::class, 'routeList'])->name('ais.pages');
                Route::get('/page-edit/{id}', [SeoPages::class, 'seoPageEdit'])->name('ais.page.edit');
                Route::post('/page-save/{id}', [SeoPages::class, 'seoPageSave'])->name('ais.page.save');

                Route::get('/update', [\App\Http\Controllers\Admin\UpdateController::class, 'index'])->name('update');
                Route::get('/logs', [\App\Http\Controllers\Admin\LogsController::class, 'index'])->name('logs');
                Route::get('/optimize-app', [MainController::class, 'optimizeApp'])->name('optimize.app');

                Route::get('/setLang/{locale}', function (string $locale) {
                    app()->setLocale($locale);
                    session()->put('locale', $locale);

                    $settingGeneral = new SettingGeneral();
                    $settingGeneral->site_language = $locale;
                    $settingGeneral->save();

                    Artisan::call('route:clear');
                    Artisan::call('route:cache');
                })->name('setLang');

                Route::resource('user', UserController::class, [
                    'names' => [
                        'index' => 'user.index',
                        'create' => 'user.create',
                        'store' => 'user.store',
                        'edit' => 'user.edit',
                        'update' => 'user.update',
                        'show' => 'user.show',
                        'destroy' => 'user.destroy',
                    ],
                ]);

                Route::post('/log-as-user', [UserController::class, 'logInAsUser'])->name('logInAsUser');

                Route::resource("/telegram-bots", AdminTelegramBotController::class,
                [
                    'names' => [
                        'index' => 'telegram-bots.index',
                        'create' => 'telegram-bots.create',
                        'store' => 'telegram-bots.store',
                        'edit' => 'telegram-bots.edit',
                        'update' => 'telegram-bots.update',
                        'show' => 'telegram-bots.show',
                        'destroy' => 'telegram-bots.destroy',
                    ],
                ]
                );

                //backup route
                Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
                Route::post('/backup', [BackupController::class, 'destroy'])->name('backup.destroy');
                Route::post('/make-backup', [BackupController::class, 'makeBackup'])->name('backup.makeBackup');


                /** Ai routes */
                Route::post('/create-ai-task', [AiQueryController::class, 'createTask'])->name('createAiTask');
                Route::post('/get-ai-task', [AiQueryController::class, 'getTaskByTaskId'])->name('getAiTask');

                /** Comment routes */
                Route::get('comments',[CommentsController::class,'index'])->name('comment.index');
                Route::get('comments/edit/{id}',[CommentsController::class,'edit'])->name('comment.edit');
                Route::put('comments/update',[CommentsController::class,'update'])->name('comment.update');
                Route::post('comments/set-status',[CommentsController::class,'setStatus'])->name('comment.setStatus');
                Route::delete('comments/destroy/{id}',[CommentsController::class,'destroy'])->name('comment.destroy');

            });
        });

        /** name without prefix "admin" **/
        Route::prefix($lang)->name($lang)->group(function () {

            Route::name('accessUi.')->prefix('access-ui')->group(static function () {
                Route::get('/', [AccessUiController::class, 'main']);

                Route::apiResource('/rules-data', RulesController::class)
                    ->parameters(['rules-data' => 'id'])
                    ->except(['show']);

                Route::apiResource('/owners-data', OwnersController::class)
                    ->parameters(['owners-data' => 'id'])
                    ->except(['show']);

                Route::apiResource('/owner.inherit-data', InheritController::class)
                    ->parameters(['inherit-data' => 'id'])
                    ->except(['show', 'update']);

                Route::apiResource('/owner.permission-data', PermissionController::class)
                    ->parameters(['permission-data' => 'id'])
                    ->except(['show', 'store', 'destroy']);
            });
        });

    }

});

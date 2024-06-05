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

        Route::prefix($lang)->group(function () use ($lang) {
            Route::prefix(SettingGeneral::value('admin_prefix'))->group(function () use ($lang) {
                Route::get('/', [MainController::class, 'index'])->name('admin.'.$lang.'index');
                Route::get('/configuration', [MainController::class, 'configuration'])->name('admin.'.$lang.'configuration');
                Route::post('/configuration', [MainController::class, 'configuration'])->name('admin.'.$lang.'configuration.save');
                Route::get('/configuration/robots_txt', [MainController::class, 'robotsTxt'])->name('admin.'.$lang.'configuration.robots_txt');
                Route::post('/configuration/robots_txt', [MainController::class, 'robotsTxt'])->name('admin.'.$lang.'configuration.robots_txt.save');

                Route::get('/modules/common-configuration', [ModuleMainController::class, 'commonConfiguration'])->name('admin.'.$lang.'modules.main.config');
                Route::post('/modules/common-configuration', [ModuleMainController::class, 'commonConfigurationSave'])->name('admin.'.$lang.'modules.main.config.save');

                Route::get('/routes', [SeoPages::class, 'routeList'])->name('admin.'.$lang.'ais.pages');
                Route::get('/page-edit/{id}', [SeoPages::class, 'seoPageEdit'])->name('admin.'.$lang.'ais.page.edit');
                Route::post('/page-save/{id}', [SeoPages::class, 'seoPageSave'])->name('admin.'.$lang.'ais.page.save');

                Route::get('/update', [\App\Http\Controllers\Admin\UpdateController::class, 'index'])->name('admin.'.$lang.'update');
                Route::get('/logs', [\App\Http\Controllers\Admin\LogsController::class, 'index'])->name('admin.'.$lang.'logs');
                Route::get('/optimize-app', [MainController::class, 'optimizeApp'])->name('admin.'.$lang.'optimize.app');

                Route::get('/setLang/{locale}', function (string $locale) {
                    app()->setLocale($locale);
                    session()->put('locale', $locale);

                    $settingGeneral = new SettingGeneral();
                    $settingGeneral->site_language = $locale;
                    $settingGeneral->save();

                    Artisan::call('route:clear');
                    Artisan::call('route:cache');
                })->name('admin.'.$lang.'setLang');

                Route::resource('user', UserController::class, [
                    'names' => [
                        'index' => 'admin.'.$lang.'user.index',
                        'create' => 'admin.'.$lang.'user.create',
                        'store' => 'admin.'.$lang.'user.store',
                        'edit' => 'admin.'.$lang.'user.edit',
                        'update' => 'admin.'.$lang.'user.update',
                        'show' => 'admin.'.$lang.'user.show',
                        'destroy' => 'admin.'.$lang.'user.destroy',
                    ],
                ]);

                Route::post('/log-as-user', [UserController::class, 'logInAsUser'])->name('admin.'.$lang.'logInAsUser');

                Route::resource("/telegram-bots", AdminTelegramBotController::class,
                [
                    'names' => [
                        'index' => $lang.'telegram-bots.index',
                        'create' => $lang.'telegram-bots.create',
                        'store' => $lang.'telegram-bots.store',
                        'edit' => $lang.'telegram-bots.edit',
                        'update' => $lang.'telegram-bots.update',
                        'show' => $lang.'telegram-bots.show',
                        'destroy' => $lang.'telegram-bots.destroy',
                    ],
                ]
                );

                //backup route
                Route::get('/backup', [BackupController::class, 'index'])->name('admin.'.$lang.'backup.index');
                Route::post('/backup', [BackupController::class, 'destroy'])->name('admin.'.$lang.'backup.destroy');
                Route::post('/make-backup', [BackupController::class, 'makeBackup'])->name('admin.'.$lang.'backup.makeBackup');


                /** Ai routes */
                Route::post('/create-ai-task', [AiQueryController::class, 'createTask'])->name('admin.'.$lang.'createAiTask');
                Route::post('/get-ai-task', [AiQueryController::class, 'getTaskByTaskId'])->name('admin.'.$lang.'getAiTask');

                /** Comment routes */
                Route::get('comments',[CommentsController::class,'index'])->name('admin.'.$lang.'comment.index');
                Route::get('comments/edit/{id}',[CommentsController::class,'edit'])->name('admin.'.$lang.'comment.edit');
                Route::put('comments/update',[CommentsController::class,'update'])->name('admin.'.$lang.'comment.update');
                Route::post('comments/set-status',[CommentsController::class,'setStatus'])->name('admin.'.$lang.'comment.setStatus');
                Route::delete('comments/destroy/{id}',[CommentsController::class,'destroy'])->name('admin.'.$lang.'comment.destroy');

            });
        });

    }

});

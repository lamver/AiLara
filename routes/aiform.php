<?php

use Illuminate\Support\Facades\Route;
use App\Settings\SettingGeneral;
use App\Services\Modules\Module;
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

/** Admin routes */
Route::middleware(['auth', 'verified', 'rbac:admin'])->name('admin.')->group(function () {

    Route::prefix(SettingGeneral::value('admin_prefix') . '/module/ai-forms')->group(function () {
        Route::resource('ai-form', \App\Http\Controllers\Admin\AiForms\AiFormController::class, [
            'names' => [
                'index' => 'module.ai-form.index',
                'create' => 'module.ai-form.create',
                'store' => 'module.ai-form.store',
                'edit' => 'module.ai-form.edit',
                'update' => 'module.ai-form.update',
                'show' => 'module.ai-form.show',
                'destroy' => 'module.ai-form.destroy',
            ],
        ]);

        Route::get('/', [\App\Http\Controllers\Admin\AiForms\AiFormController::class, 'settings'])->name('module.ai-form.settings');
        Route::post('/', [\App\Http\Controllers\Admin\AiForms\AiFormController::class, 'settingsUpdate'])->name('module.ai-form.settings.update');
    });

});

if (Module::isFrontModule(Module::MODULE_AI_FORM)) {

    foreach (Translation::getLanguagesForRoute() as $lang) {
        Route::prefix($lang)->group(function () use ($lang) {
            Route::prefix(Module::getWebRoutePrefix(Module::MODULE_AI_FORM))->group(function () use ($lang) {

                if (Module::getWebRoutePrefix(Module::MODULE_AI_FORM) != '') {
                    Route::get('/', [\App\Http\Controllers\Modules\Task\TaskController::class, 'index'])->name('aiform.'.$lang.'index');
                }

                try {
                    $allForms = \App\Models\Modules\AiForm\AiForm::query()->get();
                } catch (Exception $e) {

                }

                if (isset($allForms) && is_array($allForms)) {
                    foreach ($allForms as $form) {
                        Route::get('/' . $form->slug, [\App\Http\Controllers\Modules\Task\TaskController::class, 'viewAiFormPage'])->name('aiform.'.$lang.'view.form' . '.' . str_replace("/", ".", $form->slug));
                        Route::get('/' . $form->slug . '/{slug}_{id}', [\App\Http\Controllers\Modules\Task\TaskController::class, 'view'])->name('aiform.'.$lang.'view.form' . '.' . str_replace("/", ".", trim($form->slug, '/')) . '.result.task');
                    }
                }

            });
        });
    }
}
/** web routes */


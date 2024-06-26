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

Route::get('/install_', function () {
    if (
        isset($_GET['appKey'])
        && $_GET['appKey'] == md5(env("APP_KEY"))
    ) {
        Artisan::call('key:generate');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableName = reset($table);
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists($tableName);
            Schema::enableForeignKeyConstraints();

            echo 'Delete table:' . $tableName . '<br>';
        }

        Artisan::call('migrate');
        Artisan::call('route:clear');
        Artisan::call('route:cache');

        $user = new \App\Models\User();
        $userPassword = \Illuminate\Support\Str::random(10);
        $user->password = \Illuminate\Support\Facades\Hash::make($userPassword);
        $userLogin = 'root@'.request()->getHttpHost();
        $user->email = $userLogin;
        $user->name = 'Admin Root';
        $user->save();

        Artisan::call('db:seed --class=CreateAdminRoleAndAddToUsersSeeder');

        echo 'Your login: <b>' . $userLogin . '</b><br>';
        echo 'Your password: <b>' . $userPassword . '</b><br>';
        echo 'Front: <a target="_blank" href="/">Front</a><br>';
        echo 'Admin Dashboard: <a target="_blank" href="/' . Config::get('ailara.admin_prefix') . '">'.Config::get('ailara.admin_prefix').'</a><br>';


        return str_replace("\n", "<br>", Artisan::output());
    }

    return 'false';
});

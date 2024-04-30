<?php

namespace App\Providers;

use App\Settings\SettingGeneral;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Config::set('accessUi.path', app(SettingGeneral::class)->admin_prefix . '/access-ui');
        Config::set('translation-manager.route.prefix', app(SettingGeneral::class)->admin_prefix . '/translations');
    }
}

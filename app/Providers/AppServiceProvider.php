<?php

namespace App\Providers;

use App\Services\Translation\Translation;
use App\Settings\SettingGeneral;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
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
        $langs = Translation::getLanguages();

        View::share(['languages' => $langs]);

        $this->setEmail();
    }

    /**
     * Set the email configuration settings.
     *
     */
    private function setEmail(): void
    {
        $SettingGeneral = new SettingGeneral();

        Config::set('mail.mailers.smtp.host', $SettingGeneral->mail_host);
        Config::set('mail.mailers.smtp.transport', $SettingGeneral->mail_mailer);
        Config::set('mail.mailers.smtp.port', $SettingGeneral->mail_port);
        Config::set('mail.mailers.smtp.encryption', $SettingGeneral->mail_encryption);
        Config::set('mail.mailers.smtp.username', $SettingGeneral->mail_username);
        Config::set('mail.mailers.smtp.password', $SettingGeneral->mail_password);

        Config::set('mail.from.address', $SettingGeneral->mail_from_address);

        $nameFrom = !empty($SettingGeneral->mail_from_name) ? $SettingGeneral->mail_from_name : Config::get('APP_NAME');
        Config::set('mail.from.name', $nameFrom);

    }

}

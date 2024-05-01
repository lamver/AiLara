<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Settings\SettingGeneral;
use Exception;
use Illuminate\Support\Facades\Config;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:blog-import')->everyFourHours();
        $schedule->command('sitemap:generate')->everySixHours();

        $this->backupSchedule($schedule);

    }

    /**
     * @param $schedule
     * @return void
     */
    protected function backupSchedule($schedule): void {
        $settingGeneral = new SettingGeneral();

        if ($settingGeneral->backup_status) {
            $this->setBackupSettings($settingGeneral);
            foreach ($settingGeneral->backup_frequency as $key => $value) {

                if ($value) {
                    try {
                        $schedule->command('backup:run')->{$key}();
                    }catch (Exception $exception) {
                        echo $exception->getMessage();
                    }

                    return;
                }

            }
        }

    }

    /**
     * @param SettingGeneral $settingGeneral
     * @return void
     */
    protected function setBackupSettings(SettingGeneral $settingGeneral): void {

        if ($settingGeneral->backup_musqldump) {
            Config::set('backup.backup.source.databases', [
                'mysql',
            ]);
        }

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

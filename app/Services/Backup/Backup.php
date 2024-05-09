<?php

namespace App\Services\Backup;

use App\Settings\SettingGeneral;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Backup
{
    /**
     * @param Schedule $schedule
     * @return void
     */
    public static function backupSchedule(Schedule $schedule): void
    {
        $settingGeneral = new SettingGeneral();

        if ($settingGeneral->backup_status) {
            try {
                self::setBackupSettings($settingGeneral);

                /** @see SettingGeneral::BACKUP_FREQUENCY */
                $period = array_search(true, $settingGeneral->backup_frequency);
                $schedule->command('backup:run')->{$period}();

            } catch (Exception $exception) {
                Log::channel('backup')->error(__METHOD__ . '---' . $exception->getMessage());
            }
        }

    }

    /**
     * @param SettingGeneral $settingGeneral
     * @return void
     */
    protected static function setBackupSettings(SettingGeneral $settingGeneral): void
    {

        if ($settingGeneral->backup_musqldump) {
            // Set mysql in case if it is turned off
            Config::set('backup.backup.source.databases', [
                'mysql',
            ]);
        }

    }
}

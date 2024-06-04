<?php

namespace App\Services\Backup;

use App\Settings\SettingGeneral;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

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
                chmod($storagePath = storage_path('app/public/temp/'), 775);
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
     * @throws Exception
     */
    public static function makeBackup(SettingGeneral $settingGeneral): void
    {
        $pathDir = storage_path('app/public/temp/');

        if (!file_exists($pathDir)) {
            mkdir($pathDir, 0777, true);
        }

        chmod(storage_path('app/public/temp/'), 775);

        $backupJob = BackupJobFactory::createFromArray(static::getBackupSettings($settingGeneral));
        $backupJob->run();

    }

    /**
     * @param SettingGeneral $settingGeneral
     * @return array
     */
    protected static function getBackupSettings(SettingGeneral $settingGeneral): array
    {
        $backupSettings = Config::get('backup');

        if ($settingGeneral->backup_musqldump) {
            static::setBackupSettings($settingGeneral);
            $backupSettings['backup']['source']['databases'][] = 'mysql';
        }

        return $backupSettings;
    }

    /**
     * @param SettingGeneral $settingGeneral
     * @return void
     */
    protected static function setBackupSettings(SettingGeneral $settingGeneral): void
    {

        if ($settingGeneral->backup_musqldump) {
            $databaseMysql = Config::get('database.connections.mysql');
            // Set mysql in case if it is turned off
            $databaseMysql['dump'] = [
                'dump_binary_path' => $settingGeneral->backup_musqldump_path,
                'use_single_transaction' => true,
                'timeout' => 60 * 5,
            ];

            Config::set('database.connections.mysql', $databaseMysql);
        }

    }
}

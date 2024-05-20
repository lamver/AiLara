<?php

namespace App\Console;

use App\Helpers\CronMaster;
use App\Models\Modules\Blog\Import;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $importTasks = false;

        try {
            $importTasks = Import::query()->select(['cron_frequency'])->where(['cron' => 1])->get();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        if ($importTasks) {
           foreach ($importTasks as $task) {
               if (
                   !empty($task->cron_frequency)
                   && CronMaster::isValidFrequency($task->cron_frequency)
               ) {
                   $schedule->command('app:blog-import')->{$task->cron_frequency}();
               }
           }
        }

        $schedule->command('sitemap:generate')->everySixHours();
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

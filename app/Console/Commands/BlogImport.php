<?php

namespace App\Console\Commands;

use App\Models\Modules\Blog\Import;
use App\Models\Modules\Blog\ImportScenario;
use App\Services\AiSearchApi;
use Illuminate\Console\Command;

class BlogImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:blog-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $imports = Import::query()
            ->where(['cron' => 1])
/*            ->where(['id' => 3])*/
            ->get();

        foreach ($imports as $import) {
            Import::execute($import);
        }
    }
}

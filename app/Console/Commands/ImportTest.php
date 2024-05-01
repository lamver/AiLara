<?php

namespace App\Console\Commands;

use App\Models\Modules\Blog\Import;
use Illuminate\Console\Command;

class ImportTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-test';

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

        $import = Import::query()->find(2);

        Import::execute($import);


        //$import =

    }
}

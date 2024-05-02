<?php

namespace App\Console\Commands;

use App\Services\Update\Update;
use Illuminate\Console\Command;

class UpdateTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-test';

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
        $updateServiceDownload = Update::downloadArchiveRepository();
        $updateServiceExtract = Update::extractArchiveRepository();
        $updateServiceCandidate = Update::getFileToCandidateUpdate();

        dd($updateServiceDownload, $updateServiceExtract, /*$updateServiceCandidate*/);


    }
}

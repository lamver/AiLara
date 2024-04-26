<?php

namespace App\Http\Controllers\Admin;

use App\Services\Update\Update;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(Request $request)
    {
        $updateLog = [];
        $result = true;

        if (Update::downloadArchiveRepository()) {
            $updateLog[] = 'Archive was download';
        } else {
            $updateLog[] = 'Error archive download';

            $result = false;
        }

        if ($result && Update::extractArchiveRepository()) {
            $updateLog[] = 'Extract archive success';
        } else {
            $updateLog[] = 'Error extract archive';

            $result = false;
        }

        $fileCandidateToUpdate = Update::getFileToCandidateUpdate();

        if ($result && count($fileCandidateToUpdate) > 0) {
            foreach ($fileCandidateToUpdate as $filePath) {
                if (is_bool($updateFile = Update::updateFile($filePath))) {
                    $updateLog[] = 'File ' . json_encode($filePath) . ' was updated';
                } else {
                    $updateLog[] = 'File ' . json_encode($filePath) . ' - ' . $updateFile;
                }
            }

            $updateLog[] = 'File candidate exists';

            $result = true;
        } else {
            $updateLog[] = 'No file candidate to update';

            $result = false;
        }

        if ($result) {
            $composerProcess = Update::composerUpdate();

            foreach ($composerProcess as $process) {
                $updateLog[] = 'File ' . $process . ' was updated';
            }
        }

        if ($result) {
            $updateLog[] = 'Migrate ' . Update::migrate();
        }

        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('route:clear');
        Artisan::call('route:cache');
        Artisan::call('view:clear');

        return view('admin.update', ['updateLog' => $updateLog, 'result' => $result]);
    }

}

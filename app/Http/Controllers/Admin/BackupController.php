<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends BaseController
{

    /**
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {

        $backups = Storage::disk('local')->allFiles(config('backup.backup.name'));

        $dataArray = [];

        foreach ($backups as $backup) {
            $dataArray[] = ['name' => $backup, 'url' => Storage::url($backup)];
        }

        return view('admin.backup.index', ['backups' => $dataArray]);

    }

    /**
     * @return RedirectResponse
     */
    public function makeBackup(): RedirectResponse
    {
        Artisan::call('backup:run');

        return redirect()->route('admin.backup.index');

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->get('fileName')) {
            Storage::disk('local')->delete($request->get('fileName'));
        }

        return redirect()->route('admin.backup.index');

    }

}

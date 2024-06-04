<?php

namespace App\Http\Controllers\Admin;

use App\Services\Backup\Backup;
use App\Settings\SettingGeneral;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

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
     * @param SettingGeneral $settingGeneral
     * @return RedirectResponse
     */
    public function makeBackup(SettingGeneral $settingGeneral): RedirectResponse
    {
        try {
            Backup::makeBackup($settingGeneral);
        } catch (BindingResolutionException|Exception $e) {
            Log::channel('backup')->error($e->getMessage());
            return redirect()->route('admin.backup.index')->withErrors(['msg' => __('admin.Something went wrong')]);
        }

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

<?php

namespace App\Http\Controllers\Admin;

use App\Settings\SettingGeneral;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Config;

class BackupController extends BaseController
{

    public function __construct(SettingGeneral $settingGeneral) {
        if ($settingGeneral->backup_musqldump) {
            Config::set('backup.backup.source.databases', [
                'mysql',
            ]);
        }
    }
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
        try {
            Artisan::call('backup:run');
        }catch (BindingResolutionException $exception){
            Log::error($exception->getMessage());
            return redirect()->route('admin.backup.index')->withErrors(['msg' => 'Somthing went wrong']);
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

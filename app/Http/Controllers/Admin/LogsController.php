<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class LogsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $files = Storage::disk('logs')->allFiles();

        $viewFile = null;

        if ($logFile = $request->get('logFile', false)) {
            if (Storage::disk('logs')->exists($logFile)) {
                $viewFile = Storage::disk('logs')->get($logFile);
            }
        }

        return view('admin.logs', ['files' => $files, 'viewFile' => $viewFile]);
    }
}

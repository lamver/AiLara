<?php

namespace App\Http\Controllers\Admin;

use App\Services\Update\Update;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use GitWrapper\GitWrapper;
use Illuminate\Support\Facades\Storage;

class UpdateController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        return view('admin.update');
    }

}

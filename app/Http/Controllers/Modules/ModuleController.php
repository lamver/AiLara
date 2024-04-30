<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\Blog\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Class ModuleController
 *
 * @package App\Http\Controllers\Modules
 */
class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $modulesConfig = Config::get('modules');

        foreach ($modulesConfig as $config) {
            if (\Illuminate\Support\Facades\Request::path() == $config['route_prefix']) {
                $controller = new $config['controller']();
                return $controller->{$config['action']}($request);
            }
        }

        return abort(404);
    }
}

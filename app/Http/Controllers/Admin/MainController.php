<?php


namespace App\Http\Controllers\Admin;


use App\Models\AiLaraConfig;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class MainController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        return view('admin.index', ['slot' => 'dd']);
    }

    public function configuration(Request $request)
    {
        $aiLaraConfig = new AiLaraConfig();

        if (!empty($request->post())) {
            $aiLaraConfig->save($request->post());
            sleep(5);
            return redirect(route('admin.configuration'));
        }

        $config = $aiLaraConfig->getAll();

        return view('admin.configuration', ['config' => $config]);
    }

}

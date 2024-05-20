<?php


namespace App\Http\Controllers\Admin;


use App\Models\ModulesCommonConfig;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ModuleMainController extends BaseController
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function commonConfiguration(Request $request)
    {

        $moulesConfig = ModulesCommonConfig::query()->get();

        return view('admin.modules.common-conf', ['moulesConfig' => $moulesConfig]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function commonConfigurationSave(Request $request)
    {
        if (is_string($result = ModulesCommonConfig::store($request->post()))) {
            session()->flash('message_warning', $result);
        }

        return redirect(route('admin.modules.main.config'));
    }

}

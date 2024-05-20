<?php


namespace App\Http\Controllers\Ajax;

use App\Models\Modules\AiForm\AiForm;
use Illuminate\Contracts\Foundation\Application as ContractApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ContractView;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class AiFormController
 *
 * @package App\Http\Controllers\Ajax
 */
class AiFormController extends BaseController
{
    /**
     * @return Factory|View|ContractApplication
     */
    public function template(): Factory|View|ContractApplication
    {
        return view('ajax.aiform.v1.template', []);
    }

    /**
     * @return ContractApplication|Factory|ContractView|Application|View
     */
    public function js()
    {
        return view('ajax.aiform.v1.js', []);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getFormConfig(Request $request)
    {
        $form = AiForm::getForm((int)$request->id);

        return $form['form_config'];
    }
}

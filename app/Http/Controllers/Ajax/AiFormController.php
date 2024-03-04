<?php


namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class AiFormController
 *
 * @package App\Http\Controllers\Ajax
 */
class AiFormController extends BaseController
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function template(Request $request) : \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\Foundation\Application
    {
        return view('ajax.aiform.v1.template', []);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function js(Request $request) : \Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('ajax.aiform.v1.js', []);
    }

    public function execute(Request $request)
    {
        return ['result' => true];
    }

}

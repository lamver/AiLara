<?php


namespace App\Http\Controllers\Admin\Integration;

use App\Models\AiForm;
use App\Services\AiSearchApi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;

class AiSearchController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private object $aiSearch;

    public function __construct()
    {
        $this->aiSearch = new AiSearchApi(Config::get('ailara.api_key_aisearch'), Config::get('ailara.api_host'));

        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function commonData(Request $request) : \Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $result = $this->aiSearch->getUserData();
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        return view('admin.integration.ais.common-data', ['result' => $result]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function aiForms(Request $request) : \Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $aiFormsConfig = AiForm::query()->orderBy('id', 'desc')->get();
//dd($aiFormsConfig);
        return view('admin.integration.ais.ai-forms', ['aiFormsConfig' => $aiFormsConfig]);
    }

    public function newForm(Request $request)
    {
        try {
            $allTypesTasks = $this->aiSearch->getAllTypesTask();
        } catch (\Exception $e) {
            $allTypesTasks = $e->getMessage();
        }

        $prototypeFormJson = AiForm::getFormConfig();

        return view('admin.integration.ais.new-form', [
            'allTypesTasks' => $allTypesTasks,
            'prototypeForm' => json_encode($prototypeFormJson),
        ]);
    }

    public function newFormCreate(Request $request)
    {
        $formConfig = $request->post('form_config');
        $formConfigArray = json_decode($formConfig);

        $aiFormModel = new AiForm();
        $aiFormModel->name = $request->post('name');
        $aiFormModel->form_config = json_encode($formConfigArray); //$request->post('form_config');
        $aiFormModel->save();

        return redirect(route('admin.ais.aiForms'));
    }

    public function formEdit(Request $request, $formId)
    {
        $formConfig = AiForm::query()->where(['id' => $formId])->first();
        return view('admin.integration.ais.form-edit', ['formConfig' => $formConfig]);
    }

    public function formDelete(Request $request, $formId)
    {
        $res = AiForm::where('id',$formId)->delete();
        return redirect(route('admin.ais.aiForms'));
        dd($res);
        $formConfig = AiForm::query()->where(['id' => $formId])->first();
        return view('admin.integration.ais.form-edit', ['formConfig' => $formConfig]);
    }
}

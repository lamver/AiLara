<?php


namespace App\Http\Controllers\Admin\Integration;

use App\Models\AiForm;
use App\Services\AiSearchApi;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ContractsView;
use \Illuminate\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Foundation\Application as ContractApplication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;

class AiSearchController extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    private object $aiSearch;

    public function __construct()
    {
        $this->aiSearch = new AiSearchApi(Config::get('ailara.api_key_aisearch'), Config::get('ailara.api_host'));

        return $this;
    }

    /**
     * @param Request $request
     * @return Application|View|Factory
     */
    public function commonData(Request $request): Application|View|Factory
    {
        try {
            $result = $this->aiSearch->getUserData();
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        return view('admin.integration.ais.common-data', ['result' => $result]);
    }

    public function pages(Request $request): Application|View|Factory
    {
        try {
            $result = $this->aiSearch->getUserData();
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        return view('admin.integration.pages', ['result' => $result]);
    }

    /**
     * @return Application|View|Factory
     */
    public function aiForms(): Application|View|Factory
    {
        $aiFormsConfig = AiForm::query()->orderBy('id', 'desc')->get();

        return view('admin.integration.ais.ai-forms', ['aiFormsConfig' => $aiFormsConfig]);
    }

    /**
     * @return ContractApplication|Factory|ContractsView|Application|View
     */
    public function newForm()
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

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
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

    /**
     * @param Request $request
     * @param $formId
     * @return ContractApplication|Factory|ContractsView|Application|RedirectResponse|View
     */
    public function formEdit(Request $request, $formId)
    {
        if ($request->method() === "GET") {
            $formConfig = AiForm::query()->where(['id' => $formId])->first();
            return view('admin.integration.ais.form-edit', ['formConfig' => $formConfig]);
        }

        $aiForm = AiForm::find($formId);
        $aiForm->name = $request->name;
        $aiForm->form_config = $request->form_config;
        $aiForm->save();

        return redirect()->route('admin.ais.aiForms');
    }

    /**
     * @param $formId
     * @return ContractApplication|Application|RedirectResponse|Redirector
     */
    public function formDelete($formId)
    {
        AiForm::where('id', $formId)->delete();
        return redirect(route('admin.ais.aiForms'));
    }
}

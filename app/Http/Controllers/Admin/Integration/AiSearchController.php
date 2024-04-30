<?php


namespace App\Http\Controllers\Admin\Integration;

use App\Models\AiForm;
use App\Services\AiSearchApi;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ContractsView;
use Illuminate\Database\QueryException;
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

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
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
        $prototypeFormJson = AiForm::getFormConfig();

        return view('admin.integration.ais.new-form', [
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
        try {
            AiForm::where('id', $formId)->delete();
            return redirect(route('admin.ais.aiForms'));
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['error' => end($e->errorInfo)])->withInput();
        }

    }
}

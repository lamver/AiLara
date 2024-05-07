<?php


namespace App\Http\Controllers\Ajax;

use App\Http\Requests\TaskExecuteRequest;
use App\Models\Modules\AiForm\AiForm;
use App\Models\Tasks;
use App\Services\AiSearchApi;
use Illuminate\Contracts\Foundation\Application as ContractApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ContractView;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use JetBrains\PhpStorm\ArrayShape;

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

    /**
     * @param TaskExecuteRequest $request
     * @param AiSearchApi $aiSearchApi
     * @return bool[]
     */
    public function execute(TaskExecuteRequest $request, AiSearchApi $aiSearchApi): array
    {
        $executed = RateLimiter::attempt(
            'send-message:' . $request->ip(),
            $perMinute = 5,
            function () {
                // Send message...
            },
            $decayRate = 120,
        );

        if (!$executed) {
            return $this->resultError('Too many messages sent from your ip address!');
        }

        $aiForm = AiForm::query()->find($request->post('form_id'))->first();

        $formConfig = json_decode($aiForm->form_config, true);

        $promptMask = $formConfig['tasks'][$request->post('task_id')]['prompt_mask'];

        $promptMask = AiForm::fillPromptMask($promptMask, $request->post());
        $task = Tasks::createTask($request->post());

        $resultApi = $aiSearchApi->taskCreate(['prompt' => $promptMask]);

        if (empty($resultApi) || !$resultApi['result']) {
            return $this->resultError("Result returned false");
        }

        $task->task_id = $request->post('task_id');
        $task->external_task_id = $resultApi['task_id'];
        $task->user_id = Auth::id() ?? 0;
        $task->save();

        $aiFormRoute = AiForm::fillAiFormRoute($request->post('form_id'));

        $result = [
            'task_id' => $resultApi['task_id'],
            'task_url' => route($aiFormRoute, ['slug' => Tasks::createSlugFromUserParams($request->post()), 'id' => $task->id]),
        ];

        return $this->resultSuccessfull($result);
    }

    /**
     * @param array $data
     * @return array
     */
    #[ArrayShape(['result' => "bool", 'message' => "array"])] private function resultSuccessfull(string|array $data)
    {
        return [
            'result' => true,
            'data' => $data,
        ];
    }

    /**
     * @param $message
     *
     * @return array
     */
    #[ArrayShape(['result' => "false", 'message' => ""])] private function resultError($message)
    {
        return [
            'result' => false,
            'message' => $message,
        ];
    }

}

<?php


namespace App\Http\Controllers\Ajax;

use App\Http\Requests\TaskExecuteRequest;
use App\Models\AiForm;
use App\Models\Tasks;
use App\Services\AiSearchApi;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Foundation\Application as ContractApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ContractView;
use \Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\RateLimiter;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class AiFormController
 *
 * @package App\Http\Controllers\Ajax
 */
class AiFormController extends BaseController
{
    public string $maskaAi = "Расскажи что может означать этот сон: {{params}} для человека {{sex}} по имени {{name}} в возрасте {{age}}";

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

        $task = Tasks::createTask($request->post());

        $promptMask = strtr($this->maskaAi, [
            '{{params}}' => $request->prompt,
            '{{sex}}' => $request->sex,
            '{{name}}' => $request->name,
            '{{age}}' => $request->age,
        ]);

        $resultApi = $aiSearchApi->taskCreate(['prompt' => $promptMask]);

        if (empty($resultApi) || !$resultApi['result']) {
            return $this->resultError("Result returned false");
        }

        $task->task_id = $resultApi['task_id'];
        $task->save();

        $result = [
            'task_id' => $resultApi['task_id'],
            'task_url' => "/" . Tasks::createSlugFromUserParams($request->post()) . '/' . $task->id,
        ];

        return $this->resultSuccessfull($result);
    }

    /**
     * @param array $data
     * @return array
     */
    #[ArrayShape(['result' => "bool", 'message' => "array"])] private function resultSuccessfull(array $data)
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

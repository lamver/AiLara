<?php


namespace App\Http\Controllers\Ajax;

use App\Http\Requests\TaskExecuteRequest;
use App\Models\AiForm;
use App\Models\Tasks;
use App\Services\AiSearchApi;
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

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getFormConfig(Request $request)
    {
        return AiForm::getFormConfig();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return bool[]
     */
    public function execute(TaskExecuteRequest $request, AiSearchApi $aiSearchApi)
    {
        $executed = RateLimiter::attempt(
            'send-message:'.$request->ip(),
            $perMinute = 5,
            function() {
                // Send message...
            },
            $decayRate = 120,
        );

        if (! $executed) {
            return $this->resultError('Too many messages sent from your ip address!');
        }

        $task = Tasks::createTask($request->post());

        $resultApi = $aiSearchApi->taskCreate(json_decode($task->user_params,true)['prompt']);

        if ($resultApi['result']) {
            return $this->resultError("Result returned false");
        }

        $task->task_id = $resultApi['task_id'];
        $task->save();

        $result = [
            'task_id' => $resultApi['task_id'],
            'task_url' => "/".Tasks::createSlugFromUserParams($request->post()).'/'.$task->id,
        ];

        return $this->resultSuccessfull($result);
    }

    /**
     * @param $message
     *
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

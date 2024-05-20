<?php

namespace App\Http\Controllers\Api\V1\Module\AiForm;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Requests\TaskExecuteRequest;
use App\Models\Modules\AiForm\AiForm;
use App\Models\Tasks;
use App\Services\AiSearchApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AiFromController extends ApiBaseController
{
    /**
     * @param TaskExecuteRequest $request
     * @param AiSearchApi $aiSearchApi
     * @return bool[]
     */
    public function execute(TaskExecuteRequest $request, AiSearchApi $aiSearchApi): array
    {
        $executed = RateLimiter::attempt(
            'send-message:' . $request->ip(),
            $perMinute = 50,
            function () {
                // Send message...
            },
            $decayRate = 120,
        );

        if (!$executed) {
            return $this->resultError('Too many messages sent from your ip address!');
        }

        try {
            $aiForm = AiForm::query()->find($request->post('form_id'))->first();
        } catch (\Exception $e) {
            return $this->resultError($e->getMessage());
        }

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
            'task_id' => $task->id,
            'external_task_id' => $resultApi['task_id'],
            'task_url' => route($aiFormRoute, ['slug' => Tasks::createSlugFromUserParams($request->post()), 'id' => $task->id]),
        ];

        return $this->success($result);
    }
}

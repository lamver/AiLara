<?php

namespace App\Http\Controllers\Api\V1\Module\AiForm;

use App\Models\Tasks;
use App\Services\AiSearchApi;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;

/**
 * Class AiFormController
 *
 * @package App\Http\Controllers\Ajax
 */
class AiTaskController extends BaseController
{

    use HttpResponses;

    /**
     * @var AiSearchApi
     */
    private AiSearchApi $aiSearch;

    public function __construct()
    {
        $this->aiSearch = new AiSearchApi(
            Config::get('ailara.api_key_aisearch'),
            Config::get('ailara.api_host')
        );
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|JsonResponse|null
     */
    public function result(Request $request, $id): array|JsonResponse|null
    {
        $task = Tasks::find((int)$id);

        if (!$task) {
            return $this->responseError([], 'Task not found');
        }

        if (in_array($task->status, [Tasks::STATUS_CREATED, Tasks::STATUS_IN_PROGRESS])) {
            $result = $this->aiSearch->getTaskByTaskId($task->external_task_id);

            if (
                $result['result'] === true
                && isset($result['answer'])
                && isset($result['answer']['status'])
                && $result['answer']['status'] == 1
            ) {
                $task->status = Tasks::STATUS_DONE_SUCCESSFULLY;
                $task->result = $result['answer']['answer'];
                $task->save();
            }
        }

        return $this->responseSuccess($task->toArray());
    }

}

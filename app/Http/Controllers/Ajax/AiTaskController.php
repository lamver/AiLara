<?php

namespace App\Http\Controllers\Ajax;

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
     *
     * @return array|JsonResponse|null
     */
    public function getTask(Request $request): array|JsonResponse|null
    {
        $task = Tasks::find((int)$request->task_id);

        if ( ! $task) {
            return $this->responseError([], 'Task not found');
        }

        $result = $this->aiSearch->getTaskByTaskId($task->task_id);

        if ($result['result'] !== true) {
            return $this->responseError([], 'Something went wrong.');
        }

        $task->status = $result['answer']['status'];
        $task->save();

        return $result;
    }

}

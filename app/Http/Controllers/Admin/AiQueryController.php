<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Services\AiSearchApi;
use Illuminate\Http\Response;

class AiQueryController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function createTask(Request $request): Response
    {
        $param = [
            'prompt' => $request->get('text'),
            'type_task' => $request->get('type')
        ];

        $result = (new AiSearchApi)->taskCreate($param);


        return response(json_encode($result));

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getTaskByTaskId(Request $request): Response
    {
        $result = (new AiSearchApi)->getTaskByTaskId($request->get('id'));

        return response(json_encode($result));

    }

}

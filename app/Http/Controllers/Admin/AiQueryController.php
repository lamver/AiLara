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

        $result = (new AiSearchApi)->taskCreate($request->all());

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

<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller as BaseController;

class ApiBaseController extends BaseController
{

    public function success($data = false): array
    {
        return [
            'result' => true,
            'data' => $data
        ];
    }

    public function error($message = false): array
    {
        return [
            'result' => false,
            'message' => $message
        ];
    }

}

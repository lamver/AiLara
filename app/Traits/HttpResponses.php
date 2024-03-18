<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{

    /**
     * @param array $data
     * @param string $message
     * @param int $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSuccess(array $data, string $message = "", int $status = 200): JsonResponse
    {
        return response()->json([
            'result' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseError (array $data = [], string $message = "", int $status = 404): JsonResponse
    {
        return response()->json([
            'result' => false,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

}

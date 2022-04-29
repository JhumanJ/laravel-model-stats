<?php

namespace Jhumanj\LaravelModelStats\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ValidatesRequests;
    use AuthorizesRequests;

    public function success($data = []): JsonResponse
    {
        return response()->json(array_merge([
            'type' => 'success',
        ], $data));
    }

    public function error($data = [], $statusCode = 400): JsonResponse
    {
        return response()->json(array_merge([
            'type' => 'error',
        ], $data), $statusCode);
    }
}

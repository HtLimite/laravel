<?php

namespace App\Http\Controllers\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * trait 代码复用
 * 返回一个json
 */
trait JsonController
{
    /**
     * 返回一个json
     * @param $code
     * @param $msg
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse($code, $msg, $data): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ], $code);

    }
}

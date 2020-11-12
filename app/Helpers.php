<?php

use Illuminate\Http\Response;

if (!function_exists('responseOk')) {
    /**
     * Function fo status 200
     *
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    function responseOk($data = [], $message = '')
    {
        return (new Response())->json([
            'error' => 0,
            'data' => $data,
            'message' => $message
        ], 200);
    }
}

if (!function_exists('responseDuplicate')) {
    /**
     * Function for status 400
     *
     * @param string $message
     * @return JsonResponse
     */
    function responseDuplicate($message) {
        return (new Response())->json([
            'error' => 1,
            'message' => $message
        ], 400);
    }
}

if (!function_exists('responseAuth')) {
    /**
     * Function for status 401
     *
     * @param string $message
     * @return JsonResponse
     */
    function responseAuth($message) {
        return (new Response())->json([
            'error' => 1,
            'message' => $message
        ], 401);
    }
}

if (!function_exists('responseInternalError')) {
    /**
     * Function for status 500
     *
     * @param $message
     * @return JsonResponse
     */
    function responseInternalError($message) {
        return (new Response())->json([
            'error' => 1,
            'message' => $message
        ], 500);
    }
}
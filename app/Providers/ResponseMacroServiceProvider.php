<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register the application's response macros.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('ok', function ($data = [], $message = '') {
            return Response::json([
                'error' => 0,
                'data' => $data,
                'message' => $message
            ], 200);
        });

        Response::macro('errorAuth', function ($message) {
            return Response::json([
                'error' => 1,
                'message' => $message
            ], 401);
        });

        Response::macro('errorNotFound', function () {
            return Response::json([
                'error' => 1,
                'message' => 'not found'
            ], 400);
        });

        Response::macro('errorInternal', function () {
            return Response::json([
                'error' => 1,
                'message' => 'request could not be done'
            ], 500);
        });
    }
}
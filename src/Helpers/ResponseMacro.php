<?php

namespace CharlesUwaje\ResponseMacros\Helpers;

use Illuminate\Support\Facades\Response;

class ResponseMacro
{

    public static function register(): void
    {
        Response::macro('success', function (string $message = 'Success', array $data = [], int $status = 200) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('error', function (string $message = 'Error', array $data = [], int $status = 400) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('created', function (string $message = 'Resource created successfully', array $data = []) {
            return response()->json([
                'status' => 'created',
                'message' => $message,
                'data' => $data,
            ], 201);
        });

        Response::macro('unauthorized', function (string $message = 'Unauthorized', array $data = []) {
            return response()->json([
                'status' => 'unauthorized',
                'message' => $message,
                'data' => $data,
            ], 401);
        });

        Response::macro('forbidden', function (string $message = 'Forbidden', array $data = []) {
            return response()->json([
                'status' => 'forbidden',
                'message' => $message,
                'data' => $data,
            ], 403);
        });

        Response::macro('notFound', function (string $message = 'Not Found', array $data = []) {
            return response()->json([
                'status' => 'notFound',
                'message' => $message,
                'data' => $data,
            ], 404);
        });

        Response::macro('validationError', function (string $message = 'Validation failed', array $errors = []) {
            return response()->json([
                'status' => 'validationError',
                'message' => $message,
                'errors' => $errors,
            ], 422);
        });

        Response::macro('noContent', function () {
            return response()->noContent();
        });
    }
}

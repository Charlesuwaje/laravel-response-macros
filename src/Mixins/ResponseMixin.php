<?php

namespace CharlesUwaje\ResponseMacros\Mixins;

use Illuminate\Http\JsonResponse;

/**
 * @mixin \Illuminate\Routing\ResponseFactory
 */
class ResponseMixin
{
    public function success(): \Closure
    {
        return function (string $message = 'Success', array $data = [], int $status = 200): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data,
            ], $status);
        };
    }

    public function created(): \Closure
    {
        return function (string $message = 'Resource created successfully', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'created',
                'message' => $message,
                'data'    => $data,
            ], 201);
        };
    }

    public function accepted(): \Closure
    {
        return function (string $message = 'Request accepted', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'accepted',
                'message' => $message,
                'data'    => $data,
            ], 202);
        };
    }

    public function error(): \Closure
    {
        return function (string $message = 'Error', array $data = [], int $status = 400): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'error',
                'message' => $message,
                'data'    => $data,
            ], $status);
        };
    }

    public function unauthorized(): \Closure
    {
        return function (string $message = 'Unauthorized', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'unauthorized',
                'message' => $message,
                'data'    => $data,
            ], 401);
        };
    }

    public function forbidden(): \Closure
    {
        return function (string $message = 'Forbidden', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'forbidden',
                'message' => $message,
                'data'    => $data,
            ], 403);
        };
    }

    public function notFound(): \Closure
    {
        return function (string $message = 'Not Found', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'not_found',
                'message' => $message,
                'data'    => $data,
            ], 404);
        };
    }

    public function methodNotAllowed(): \Closure
    {
        return function (string $message = 'Method Not Allowed', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'method_not_allowed',
                'message' => $message,
                'data'    => $data,
            ], 405);
        };
    }

    public function conflict(): \Closure
    {
        return function (string $message = 'Conflict', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'conflict',
                'message' => $message,
                'data'    => $data,
            ], 409);
        };
    }

    public function validationError(): \Closure
    {
        return function (string $message = 'Validation failed', array $errors = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'validation_error',
                'message' => $message,
                'errors'  => $errors,
            ], 422);
        };
    }

    public function tooManyRequests(): \Closure
    {
        return function (string $message = 'Too Many Requests', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'too_many_requests',
                'message' => $message,
                'data'    => $data,
            ], 429);
        };
    }

    public function serverError(): \Closure
    {
        return function (string $message = 'Internal Server Error', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'server_error',
                'message' => $message,
                'data'    => $data,
            ], 500);
        };
    }

    public function serviceUnavailable(): \Closure
    {
        return function (string $message = 'Service Unavailable', array $data = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'service_unavailable',
                'message' => $message,
                'data'    => $data,
            ], 503);
        };
    }

    public function paginated(): \Closure
    {
        return function (string $message = 'Success', array $data = [], array $pagination = []): JsonResponse {
            /** @var \Illuminate\Routing\ResponseFactory $this */
            return $this->json([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data,
                'meta'    => [
                    'pagination' => $pagination,
                ],
            ], 200);
        };
    }
}

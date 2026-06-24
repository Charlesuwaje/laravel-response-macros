<?php

// This file is not autoloaded. It exists only to provide IDE type hints
// for the macros registered by this package.

namespace Illuminate\Routing {

    /**
     * @method \Illuminate\Http\JsonResponse success(string $message = 'Success', array $data = [], int $status = 200)
     * @method \Illuminate\Http\JsonResponse created(string $message = 'Resource created successfully', array $data = [])
     * @method \Illuminate\Http\JsonResponse accepted(string $message = 'Request accepted', array $data = [])
     * @method \Illuminate\Http\Response     noContent()
     * @method \Illuminate\Http\JsonResponse error(string $message = 'Error', array $data = [], int $status = 400)
     * @method \Illuminate\Http\JsonResponse unauthorized(string $message = 'Unauthorized', array $data = [])
     * @method \Illuminate\Http\JsonResponse forbidden(string $message = 'Forbidden', array $data = [])
     * @method \Illuminate\Http\JsonResponse notFound(string $message = 'Not Found', array $data = [])
     * @method \Illuminate\Http\JsonResponse methodNotAllowed(string $message = 'Method Not Allowed', array $data = [])
     * @method \Illuminate\Http\JsonResponse conflict(string $message = 'Conflict', array $data = [])
     * @method \Illuminate\Http\JsonResponse validationError(string $message = 'Validation failed', array $errors = [])
     * @method \Illuminate\Http\JsonResponse tooManyRequests(string $message = 'Too Many Requests', array $data = [])
     * @method \Illuminate\Http\JsonResponse serverError(string $message = 'Internal Server Error', array $data = [])
     * @method \Illuminate\Http\JsonResponse serviceUnavailable(string $message = 'Service Unavailable', array $data = [])
     * @method \Illuminate\Http\JsonResponse paginated(string $message = 'Success', array $data = [], array $pagination = [])
     */
    class ResponseFactory {}
}

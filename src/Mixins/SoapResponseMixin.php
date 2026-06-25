<?php

namespace CharlesUwaje\ResponseMacros\Mixins;

use CharlesUwaje\ResponseMacros\Support\SoapXmlBuilder;
use Illuminate\Http\Response;

/**
 * @mixin \Illuminate\Routing\ResponseFactory
 */
class SoapResponseMixin
{
    public function success(): \Closure
    {
        return function (string $message = 'Success', array $data = [], int $status = 200): Response {
            return SoapXmlBuilder::envelope('success', $message, $data, $status);
        };
    }

    public function created(): \Closure
    {
        return function (string $message = 'Resource created successfully', array $data = []): Response {
            return SoapXmlBuilder::envelope('created', $message, $data, 201);
        };
    }

    public function accepted(): \Closure
    {
        return function (string $message = 'Request accepted', array $data = []): Response {
            return SoapXmlBuilder::envelope('accepted', $message, $data, 202);
        };
    }

    public function error(): \Closure
    {
        return function (string $message = 'Error', array $data = [], int $status = 400): Response {
            return SoapXmlBuilder::fault('Client', $message, $status, $data);
        };
    }

    public function unauthorized(): \Closure
    {
        return function (string $message = 'Unauthorized', array $data = []): Response {
            return SoapXmlBuilder::fault('Client', $message, 401, $data);
        };
    }

    public function forbidden(): \Closure
    {
        return function (string $message = 'Forbidden', array $data = []): Response {
            return SoapXmlBuilder::fault('Client', $message, 403, $data);
        };
    }

    public function notFound(): \Closure
    {
        return function (string $message = 'Not Found', array $data = []): Response {
            return SoapXmlBuilder::fault('Client', $message, 404, $data);
        };
    }

    public function methodNotAllowed(): \Closure
    {
        return function (string $message = 'Method Not Allowed', array $data = []): Response {
            return SoapXmlBuilder::fault('Client', $message, 405, $data);
        };
    }

    public function conflict(): \Closure
    {
        return function (string $message = 'Conflict', array $data = []): Response {
            return SoapXmlBuilder::fault('Client', $message, 409, $data);
        };
    }

    public function validationError(): \Closure
    {
        return function (string $message = 'Validation failed', array $errors = []): Response {
            return SoapXmlBuilder::fault('Client', $message, 422, $errors);
        };
    }

    public function tooManyRequests(): \Closure
    {
        return function (string $message = 'Too Many Requests', array $data = []): Response {
            return SoapXmlBuilder::fault('Client', $message, 429, $data);
        };
    }

    public function serverError(): \Closure
    {
        return function (string $message = 'Internal Server Error', array $data = []): Response {
            return SoapXmlBuilder::fault('Server', $message, 500, $data);
        };
    }

    public function serviceUnavailable(): \Closure
    {
        return function (string $message = 'Service Unavailable', array $data = []): Response {
            return SoapXmlBuilder::fault('Server', $message, 503, $data);
        };
    }

    public function paginated(): \Closure
    {
        return function (string $message = 'Success', array $data = [], array $pagination = []): Response {
            return SoapXmlBuilder::envelope('success', $message, $data, 200, $pagination);
        };
    }
}

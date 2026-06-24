<?php

namespace CharlesUwaje\ResponseMacros\Tests;

class ResponseMacroTest extends TestCase
{
    public function test_success_returns_200_with_correct_structure(): void
    {
        $response = response()->success('OK', ['id' => 1]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'status' => 'success',
            'message' => 'OK',
            'data' => ['id' => 1],
        ], $response->getData(true));
    }

    public function test_success_accepts_custom_status_code(): void
    {
        $response = response()->success('Partial', [], 206);

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals('success', $response->getData()->status);
    }

    public function test_created_returns_201(): void
    {
        $response = response()->created('Created', ['id' => 1]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals([
            'status' => 'created',
            'message' => 'Created',
            'data' => ['id' => 1],
        ], $response->getData(true));
    }

    public function test_accepted_returns_202(): void
    {
        $response = response()->accepted('Processing');

        $this->assertEquals(202, $response->getStatusCode());
        $this->assertEquals('accepted', $response->getData()->status);
    }

    public function test_no_content_returns_204(): void
    {
        $response = response()->noContent();

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function test_error_returns_400_with_correct_structure(): void
    {
        $response = response()->error('Bad Request', ['field' => 'name']);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals([
            'status' => 'error',
            'message' => 'Bad Request',
            'data' => ['field' => 'name'],
        ], $response->getData(true));
    }

    public function test_error_accepts_custom_status_code(): void
    {
        $response = response()->error('Gone', [], 410);

        $this->assertEquals(410, $response->getStatusCode());
    }

    public function test_unauthorized_returns_401(): void
    {
        $response = response()->unauthorized('Please log in');

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('unauthorized', $response->getData()->status);
        $this->assertEquals('Please log in', $response->getData()->message);
    }

    public function test_forbidden_returns_403(): void
    {
        $response = response()->forbidden('Access denied');

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('forbidden', $response->getData()->status);
    }

    public function test_not_found_returns_404(): void
    {
        $response = response()->notFound('User not found');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('not_found', $response->getData()->status);
    }

    public function test_method_not_allowed_returns_405(): void
    {
        $response = response()->methodNotAllowed();

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals('method_not_allowed', $response->getData()->status);
    }

    public function test_conflict_returns_409(): void
    {
        $response = response()->conflict('Email already exists');

        $this->assertEquals(409, $response->getStatusCode());
        $this->assertEquals('conflict', $response->getData()->status);
    }

    public function test_validation_error_returns_422_with_errors_key(): void
    {
        $errors = ['email' => ['The email field is required.']];
        $response = response()->validationError('Validation failed', $errors);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals([
            'status' => 'validation_error',
            'message' => 'Validation failed',
            'errors' => $errors,
        ], $response->getData(true));
    }

    public function test_too_many_requests_returns_429(): void
    {
        $response = response()->tooManyRequests();

        $this->assertEquals(429, $response->getStatusCode());
        $this->assertEquals('too_many_requests', $response->getData()->status);
    }

    public function test_server_error_returns_500(): void
    {
        $response = response()->serverError('Something went wrong');

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('server_error', $response->getData()->status);
    }

    public function test_service_unavailable_returns_503(): void
    {
        $response = response()->serviceUnavailable('Down for maintenance');

        $this->assertEquals(503, $response->getStatusCode());
        $this->assertEquals('service_unavailable', $response->getData()->status);
    }

    public function test_paginated_returns_200_with_meta(): void
    {
        $data = [['id' => 1], ['id' => 2]];
        $pagination = ['total' => 100, 'per_page' => 15, 'current_page' => 1, 'last_page' => 7];

        $response = response()->paginated('Users retrieved', $data, $pagination);

        $this->assertEquals(200, $response->getStatusCode());
        $body = $response->getData(true);
        $this->assertEquals('success', $body['status']);
        $this->assertEquals($data, $body['data']);
        $this->assertEquals($pagination, $body['meta']['pagination']);
    }

    public function test_all_macros_use_application_json_content_type(): void
    {
        $responses = [
            response()->success(),
            response()->created(),
            response()->accepted(),
            response()->error(),
            response()->unauthorized(),
            response()->forbidden(),
            response()->notFound(),
            response()->methodNotAllowed(),
            response()->conflict(),
            response()->validationError(),
            response()->tooManyRequests(),
            response()->serverError(),
            response()->serviceUnavailable(),
            response()->paginated(),
        ];

        foreach ($responses as $response) {
            $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
        }
    }
}

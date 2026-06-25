<?php

namespace CharlesUwaje\ResponseMacros\Tests;

use CharlesUwaje\ResponseMacros\Support\SoapXmlBuilder;

class SoapResponseMixinTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('response-macros.format', 'soap');
    }

    private function assertSoapResponse(\Illuminate\Http\Response $response, int $status, string $contentContains): void
    {
        $this->assertEquals($status, $response->getStatusCode());
        $this->assertStringContainsString('text/xml', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('SOAP-ENV:Envelope', $response->getContent());
        $this->assertStringContainsString($contentContains, $response->getContent());
    }

    public function test_success_returns_200_soap_envelope(): void
    {
        $response = response()->success('OK', ['id' => 1]);

        $this->assertSoapResponse($response, 200, '<status>success</status>');
        $this->assertStringContainsString('<message>OK</message>', $response->getContent());
        $this->assertStringContainsString('<id>1</id>', $response->getContent());
    }

    public function test_created_returns_201_soap_envelope(): void
    {
        $response = response()->created('Created', ['id' => 5]);

        $this->assertSoapResponse($response, 201, '<status>created</status>');
    }

    public function test_accepted_returns_202_soap_envelope(): void
    {
        $response = response()->accepted('Processing');

        $this->assertSoapResponse($response, 202, '<status>accepted</status>');
    }

    public function test_error_returns_soap_fault_with_400(): void
    {
        $response = response()->error('Bad Request');

        $this->assertSoapResponse($response, 400, 'SOAP-ENV:Fault');
        $this->assertStringContainsString('<faultstring>Bad Request</faultstring>', $response->getContent());
    }

    public function test_unauthorized_returns_soap_fault_with_401(): void
    {
        $response = response()->unauthorized('Unauthorized');

        $this->assertSoapResponse($response, 401, 'SOAP-ENV:Fault');
    }

    public function test_forbidden_returns_soap_fault_with_403(): void
    {
        $response = response()->forbidden();

        $this->assertSoapResponse($response, 403, 'SOAP-ENV:Fault');
    }

    public function test_not_found_returns_soap_fault_with_404(): void
    {
        $response = response()->notFound('Not Found');

        $this->assertSoapResponse($response, 404, 'SOAP-ENV:Fault');
    }

    public function test_validation_error_returns_soap_fault_with_detail(): void
    {
        $response = response()->validationError('Validation failed', ['email' => ['Required']]);

        $this->assertSoapResponse($response, 422, 'SOAP-ENV:Fault');
        $this->assertStringContainsString('<detail>', $response->getContent());
    }

    public function test_server_error_returns_soap_fault_with_500(): void
    {
        $response = response()->serverError();

        $this->assertSoapResponse($response, 500, '<faultcode>Server</faultcode>');
    }

    public function test_service_unavailable_returns_soap_fault_with_503(): void
    {
        $response = response()->serviceUnavailable();

        $this->assertSoapResponse($response, 503, '<faultcode>Server</faultcode>');
    }

    public function test_paginated_returns_soap_envelope_with_meta(): void
    {
        $response = response()->paginated('Users', [['id' => 1]], ['total' => 10, 'per_page' => 5]);

        $this->assertSoapResponse($response, 200, '<status>success</status>');
        $this->assertStringContainsString('<meta>', $response->getContent());
        $this->assertStringContainsString('<pagination>', $response->getContent());
    }

    public function test_build_envelope_produces_valid_xml(): void
    {
        $response = SoapXmlBuilder::envelope('success', 'Test', ['key' => 'value'], 200);

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
    }

    public function test_build_fault_produces_valid_xml(): void
    {
        $response = SoapXmlBuilder::fault('Client', 'Something wrong', 400);

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
    }
}

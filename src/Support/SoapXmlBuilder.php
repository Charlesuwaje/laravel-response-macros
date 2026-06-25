<?php

namespace CharlesUwaje\ResponseMacros\Support;

use Illuminate\Http\Response;

class SoapXmlBuilder
{
    private const NS = 'http://schemas.xmlsoap.org/soap/envelope/';

    public static function envelope(
        string $status,
        string $message,
        array $data,
        int $httpStatus,
        array $pagination = []
    ): Response {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $envelope = $doc->createElementNS(self::NS, 'SOAP-ENV:Envelope');
        $doc->appendChild($envelope);

        $body = $doc->createElementNS(self::NS, 'SOAP-ENV:Body');
        $envelope->appendChild($body);

        $responseEl = $doc->createElement('response');
        $body->appendChild($responseEl);

        $responseEl->appendChild(self::text($doc, 'status', $status));
        $responseEl->appendChild(self::text($doc, 'message', $message));

        $dataEl = $doc->createElement('data');
        self::arrayToXml($doc, $dataEl, $data);
        $responseEl->appendChild($dataEl);

        if (! empty($pagination)) {
            $metaEl = $doc->createElement('meta');
            $paginationEl = $doc->createElement('pagination');
            self::arrayToXml($doc, $paginationEl, $pagination);
            $metaEl->appendChild($paginationEl);
            $responseEl->appendChild($metaEl);
        }

        return response()->make($doc->saveXML(), $httpStatus, ['Content-Type' => 'text/xml; charset=utf-8']);
    }

    public static function fault(
        string $faultCode,
        string $faultString,
        int $httpStatus,
        array $detail = []
    ): Response {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $envelope = $doc->createElementNS(self::NS, 'SOAP-ENV:Envelope');
        $doc->appendChild($envelope);

        $body = $doc->createElementNS(self::NS, 'SOAP-ENV:Body');
        $envelope->appendChild($body);

        $fault = $doc->createElementNS(self::NS, 'SOAP-ENV:Fault');
        $body->appendChild($fault);

        $fault->appendChild(self::text($doc, 'faultcode', $faultCode));
        $fault->appendChild(self::text($doc, 'faultstring', $faultString));

        if (! empty($detail)) {
            $detailEl = $doc->createElement('detail');
            self::arrayToXml($doc, $detailEl, $detail);
            $fault->appendChild($detailEl);
        }

        return response()->make($doc->saveXML(), $httpStatus, ['Content-Type' => 'text/xml; charset=utf-8']);
    }

    private static function text(\DOMDocument $doc, string $tag, string $value): \DOMElement
    {
        $el = $doc->createElement($tag);
        $el->appendChild($doc->createTextNode($value));

        return $el;
    }

    private static function arrayToXml(\DOMDocument $doc, \DOMElement $parent, array $data): void
    {
        foreach ($data as $key => $value) {
            $tag = is_numeric($key) ? 'item' : preg_replace('/[^a-zA-Z0-9_\-.]/', '_', (string) $key);
            $element = $doc->createElement($tag);

            if (is_array($value)) {
                self::arrayToXml($doc, $element, $value);
            } else {
                $element->appendChild($doc->createTextNode((string) $value));
            }

            $parent->appendChild($element);
        }
    }
}

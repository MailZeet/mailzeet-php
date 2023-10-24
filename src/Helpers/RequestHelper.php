<?php

namespace MailZeet\Helpers;

use GuzzleHttp\Psr7\Response;
use MailZeet\Exceptions\BadRequestException;
use MailZeet\Exceptions\ForbiddenException;
use MailZeet\Exceptions\InvalidPayloadException;
use MailZeet\Exceptions\InvalidResourceException;
use MailZeet\Exceptions\NotAcceptableException;
use MailZeet\Exceptions\ServerErrorException;
use MailZeet\Exceptions\ServiceUnavailableException;
use MailZeet\Exceptions\UnauthorizedException;

trait RequestHelper
{
    protected function handleResponse(Response $response)
    {
        $contents = $response->getBody()->getContents();
        $responsePayload = empty($contents)
            ? $contents
            : json_decode($contents, false, 512, JSON_THROW_ON_ERROR);

        switch ($response->getStatusCode()) {
            case 201:
            case 200:
                return $responsePayload->data ?? $responsePayload;
            case 401:
                throw new UnauthorizedException($responsePayload->message ?? 'Unauthorized, Status Code: ' . $response->getStatusCode());
            case 403:
                throw new ForbiddenException($responsePayload->message ?? 'Forbidden, Status Code: ' . $response->getStatusCode());
            case 404:
                throw new InvalidResourceException($responsePayload->message ?? 'Not Found, Status Code: ' . $response->getStatusCode());
            case 400:
                throw new BadRequestException($responsePayload->message ?? 'Bad Request, Status Code: ' . $response->getStatusCode());
            case 422:
                throw new InvalidPayloadException($responsePayload->message ?? 'Invalid Payload, Status Code: ' . $response->getStatusCode());
            case 406:
                throw new NotAcceptableException($responsePayload->message ?? 'Not Acceptable, Status Code: ' . $response->getStatusCode());
            case 503:
                throw new ServiceUnavailableException($responsePayload->message ?? 'Service Unavailable, Status Code: ' . $response->getStatusCode());
            default:
                throw new ServerErrorException($responsePayload->message ?? 'Server Error, Status Code: ' . $response->getStatusCode());
        }
    }
}

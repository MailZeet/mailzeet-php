<?php

namespace MailZeet;

use MailZeet\Configs\Config;
use MailZeet\Exceptions\InvalidPayloadException;
use MailZeet\Helpers\RequestHelper;
use MailZeet\Objects\MailObject;
use MailZeet\Utils\GuzzleWrapper;

class MailZeet
{
    use RequestHelper;

    private string $environment;

    public string $apiKey;

    /**
     *  API base URL.
     */
    public string $baseUrl;

    public function __construct(
        string $apiKey,
        bool $devMode = false,
        string $baseUrl = Config::BASE_URL
    ) {
        $this->apiKey = $apiKey;

        $this->baseUrl = ($devMode === true)
            ? $baseUrl
            : Config::BASE_URL;

        if (empty($this->apiKey)) {
            throw new InvalidPayloadException('Public key is not set or not a string.');
        }

        if (! filter_var($this->baseUrl, FILTER_VALIDATE_URL) && ! filter_var($this->baseUrl, FILTER_VALIDATE_IP)) {
            throw new InvalidPayloadException('Base url is not valid.');
        }
    }

    public function send(MailObject $emailObject): object
    {
        $httpClient = new GuzzleWrapper($this->baseUrl);

        $data = $emailObject->toArray();

        $response = $httpClient->post(
            '/email',
            $data,
            [
                'Authorization' => "Bearer {$this->apiKey}",
            ]
        );

        return $this->handleResponse($response);
    }
}

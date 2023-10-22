<?php

namespace MailZeet;

use MailZeet\Configs\Config;
use MailZeet\Exceptions\InvalidPayloadException;
use MailZeet\Helpers\RequestHelper;
use MailZeet\Objects\Mail;
use MailZeet\Utils\GuzzleWrapper;

/**
 * Class MailZeet.
 *
 * Represents the primary handler for sending emails using the MailZeet service.
 */
class MailZeet
{
    use RequestHelper;

    /**
     * @var string mailZeet API key for authentication
     */
    public string $apiKey;

    /**
     * @var string mailZeet API base URL
     */
    public string $baseUrl;

    /**
     * Constructor for the MailZeet class.
     *
     * @param string $apiKey  mailZeet's API key
     * @param bool   $devMode determines if the dev mode is activated
     * @param string $baseUrl Base URL for MailZeet's API. Defaults to the value in Config.
     *
     * @throws InvalidPayloadException if the API key is not set or if the base URL is invalid
     */
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

    /**
     * Send the email using MailZeet.
     *
     * @param Mail $emailObject an object representation of the email
     *
     * @return object the API response from MailZeet
     *@throws \JsonException
     *
     */
    public function send(Mail $emailObject): object
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

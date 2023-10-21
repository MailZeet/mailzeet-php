<?php

use GuzzleHttp\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use MailZeet\Configs\Config;
use Psr\Http\Message\ResponseInterface;

/**
 * GuzzleWrapper - A comprehensive Guzzle wrapper for making HTTP requests.
 */
class GuzzleWrapper
{
    private $client;

    private $baseUrl;

    /**
     * Constructor initializes the Guzzle client with a base URL.
     *
     * @param string $baseUrl the base URL for the HTTP client
     */
    public function __construct(string $baseUrl, GuzzleHttpClient $client = null)
    {
        $this->baseUrl = $baseUrl;
        $this->client = $client ?: new GuzzleHttpClient([
            'base_uri'    => $this->baseUrl,
            'timeout'     => Config::TIMEOUT,
            'http_errors' => false,
        ]);
    }

    /**
     * Make a GET request.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     optional data parameters
     * @param array  $headers  optional headers
     *
     * @return array the response data
     */
    public function get(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('GET', $endpoint, $data, $headers);
    }

    /**
     * Make a POST request.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     optional data parameters
     * @param array  $headers  optional headers
     *
     * @return array the response data
     */
    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('POST', $endpoint, $data, $headers);
    }

    /**
     * Make a PUT request.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     optional data parameters
     * @param array  $headers  optional headers
     *
     * @return array the response data
     */
    public function put(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('PUT', $endpoint, $data, $headers);
    }

    /**
     * Make a DELETE request.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     optional data parameters
     * @param array  $headers  optional headers
     *
     * @return array the response data
     */
    public function delete(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('DELETE', $endpoint, $data, $headers);
    }

    /**
     * Makes an HTTP request using Guzzle.
     *
     * @param string $method   HTTP method
     * @param string $endpoint API endpoint
     * @param array  $data     data parameters
     * @param array  $headers  headers
     *
     * @return ResponseInterface response data
     */
    private function request(string $method, string $endpoint, array $data, array $headers): Response
    {
        $defaultHeaders = [
            'User-Agent'    => 'MailZeet PHP SDK v' . Config::VERSION,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];

        $headers = array_merge($defaultHeaders, $headers);

        try {
            return $this->client->request(
                $method,
                $endpoint,
                [
                    'headers' => $headers,
                    'json'    => $data,
                ]
            );
        } catch (RequestException|GuzzleException $e) {
            throw new RuntimeException('HTTP Request failed: ' . $e->getMessage());
        }
    }
}

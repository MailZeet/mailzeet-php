<?php

namespace MailZeet\Utils;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use MailZeet\Configs\Config;
use RuntimeException;

/**
 * GuzzleWrapper - A comprehensive Guzzle wrapper for making HTTP requests.
 */
class GuzzleWrapper
{
    private GuzzleHttpClient $client;

    private string $baseUrl;

    /**
     * Constructor initializes the Guzzle client with a base URL.
     *
     * @param string                $baseUrl the base URL for the HTTP client
     * @param GuzzleHttpClient|null $client  (optional) custom Guzzle client
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
     * @param array  $data     (optional) data parameters
     * @param array  $headers  (optional) headers
     *
     * @return Response the response data
     */
    public function get(string $endpoint, array $data = [], array $headers = []): Response
    {
        return $this->request('GET', $endpoint, $data, $headers);
    }

    /**
     * Make a POST request.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     (optional) data parameters
     * @param array  $headers  (optional) headers
     *
     * @return Response the response data
     */
    public function post(string $endpoint, array $data = [], array $headers = []): Response
    {
        return $this->request('POST', $endpoint, $data, $headers);
    }

    /**
     * Make a PUT request.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     (optional) data parameters
     * @param array  $headers  (optional) headers
     *
     * @return Response the response data
     */
    public function put(string $endpoint, array $data = [], array $headers = []): Response
    {
        return $this->request('PUT', $endpoint, $data, $headers);
    }

    /**
     * Make a DELETE request.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     (optional) data parameters
     * @param array  $headers  (optional) headers
     *
     * @return Response the response data
     */
    public function delete(string $endpoint, array $data = [], array $headers = []): Response
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
     * @return Response response data
     */
    private function request(string $method, string $endpoint, array $data, array $headers)
    {
        $url = $this->formatUrl($endpoint);

        $defaultHeaders = [
            'User-Agent'    => 'MailZeet PHP SDK v' . Config::VERSION,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];

        $headers = array_merge($defaultHeaders, $headers);

        try {
            return $this->client->request(
                $method,
                $url,
                [
                    'headers' => $headers,
                    'json'    => $data,
                ]
            );
        } catch (RequestException|GuzzleException $e) {
            throw new RuntimeException('HTTP Request failed: ' . $e->getMessage());
        }
    }

    private function formatUrl(string $endpoint): string
    {
        $formattedBaseUrl = rtrim($this->baseUrl, '/') . '/';

        $formattedEndpoint = ltrim($endpoint, '/');

        return $formattedBaseUrl . $formattedEndpoint;
    }
}

<?php

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MailZeet\Configs\Config;
use MailZeet\Exceptions\BadRequestException;
use MailZeet\Exceptions\ForbiddenException;
use MailZeet\Exceptions\InvalidPayloadException;
use MailZeet\Exceptions\InvalidResourceException;
use MailZeet\Exceptions\NotAcceptableException;
use MailZeet\Exceptions\ServerErrorException;
use MailZeet\Exceptions\ServiceUnavailableException;
use MailZeet\Exceptions\UnauthorizedException;
use MailZeet\MailZeet;
use MailZeet\Objects\Mail;
use MailZeet\Tests\TestCase;

class MailZeetTest extends TestCase
{
    private MockHandler $mockHandler;

    protected GuzzleHttpClient $mockHttpClient;

    public function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);

        $this->mockHttpClient = new GuzzleHttpClient([
            'handler'     => $handlerStack,
            'http_errors' => false,
        ]);
    }

    /** @test */
    public function it_returns_data_for_successful_responses(): void
    {
        $responseBody = json_encode([
            'message' => 'Email sent successfully',
            'data'    => [
                'sendingId' => '1234567890',
            ],
        ], JSON_THROW_ON_ERROR);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $response = $mailZeet->send($mail);

        $this->assertEquals('1234567890', $response->sendingId);
    }

    /** @test */
    public function it_throws_unauthorized_exception_for_401_responses(): void
    {
        $this->expectException(UnauthorizedException::class);

        $this->mockHandler->append(new Response(401));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $mailZeet->send($mail);
    }

    /** @test */
    public function it_throws_forbidden_exception_for_403_responses()
    {
        $this->expectException(ForbiddenException::class);

        $this->mockHandler->append(new Response(403));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $mailZeet->send($mail);
    }

    /** @test */
    public function it_throws_invalid_resource_exception_for_404_responses()
    {
        $this->expectException(InvalidResourceException::class);

        $this->mockHandler->append(new Response(404));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $mailZeet->send($mail);
    }

    /** @test */
    public function it_throws_bad_request_exception_for_400_responses()
    {
        $this->expectException(BadRequestException::class);

        $this->mockHandler->append(new Response(400));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $mailZeet->send($mail);
    }

    /** @test */
    public function it_throws_invalid_payload_exception_for_422_responses(): void
    {
        $this->expectException(InvalidPayloadException::class);

        $this->mockHandler->append(new Response(422));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $mailZeet->send($mail);
    }

    /** @test */
    public function it_throws_not_acceptable_exception_for_406_responses(): void
    {
        $this->expectException(NotAcceptableException::class);

        $this->mockHandler->append(new Response(406));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $mailZeet->send($mail);
    }

    /** @test */
    public function it_throws_service_unavailable_exception_for_503_responses(): void
    {
        $this->expectException(ServiceUnavailableException::class);

        $this->mockHandler->append(new Response(503));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $mailZeet->send($mail);
    }

    /** @test */
    public function it_throws_server_error_exception_for_other_responses(): void
    {
        $this->expectException(ServerErrorException::class);

        $this->mockHandler->append(new Response(500));

        $mailZeet = new MailZeet('valid_api_key', false, Config::BASE_URL, $this->mockHttpClient);
        $mail = new Mail();

        $mailZeet->send($mail);
    }
}

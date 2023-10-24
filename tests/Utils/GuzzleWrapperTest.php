<?php

namespace MailZeet\Tests\Utils;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MailZeet\Tests\TestCase;
use MailZeet\Utils\GuzzleWrapper;
use ReflectionClass;
use RuntimeException;

class GuzzleWrapperTest extends TestCase
{
    protected $guzzleWrapper;

    protected $mockHandler;

    public function setUp(): void
    {
        $this->mockHandler = new MockHandler();

        $client = new GuzzleHttpClient([
            'handler' => HandlerStack::create($this->mockHandler),
        ]);

        $this->guzzleWrapper = new GuzzleWrapper('http://example.com', $client);
    }

    /** @test */
    public function it_can_make_a_get_request(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"message": "success"}'));

        $response = $this->guzzleWrapper->get('/test-endpoint');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"message": "success"}', $response->getBody());
    }

    /** @test */
    public function it_can_make_a_post_request(): void
    {
        $this->mockHandler->append(new Response(201, [], '{"id": 1}'));

        $response = $this->guzzleWrapper->post('/test-endpoint', ['name' => 'John']);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('{"id": 1}', $response->getBody());
    }

    /** @test */
    public function it_throws_runtime_exception_on_request_error(): void
    {
        $this->mockHandler->append(new RequestException('Error Communicating with Server', new \GuzzleHttp\Psr7\Request('GET', 'test')));

        $this->expectException(RuntimeException::class);

        $this->guzzleWrapper->get('/test-endpoint');
    }

    /** @test */
    public function it_can_make_a_put_request(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"updated": true}'));

        $response = $this->guzzleWrapper->put('/test-endpoint', ['name' => 'Jane']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"updated": true}', $response->getBody());
    }

    /** @test */
    public function it_can_make_a_delete_request(): void
    {
        $this->mockHandler->append(new Response(204));

        $response = $this->guzzleWrapper->delete('/test-endpoint');

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_correctly_formats_url(): void
    {
        $reflection = new ReflectionClass($this->guzzleWrapper);
        $formatUrlMethod = $reflection->getMethod('formatUrl');
        $formatUrlMethod->setAccessible(true);

        $result = $formatUrlMethod->invokeArgs($this->guzzleWrapper, ['/test-endpoint']);

        $this->assertEquals('http://example.com/test-endpoint', $result);
    }
}

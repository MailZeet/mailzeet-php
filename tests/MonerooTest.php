<?php

namespace MailZeet\Tests;

use MailZeet\Configs\Config;
use MailZeet\Exceptions\InvalidPayloadException;
use MailZeet\MailZeet;
use MailZeet\Payment;
use MailZeet\Payout;
use PHPUnit\Framework\TestCase;

class MailZeetTest extends TestCase
{
    /**
     * It should construct a MailZeet object.
     *
     * @test
     */
    public function it_should_construct_a_mailzeet_object(): void
    {
        $publicKey = 'public-key';
        $secretKey = 'secret-key';

        $mailzeet = new MailZeet($publicKey, $secretKey, false);

        $this->assertEquals($publicKey, $mailzeet->publicKey);
        $this->assertEquals($secretKey, $mailzeet->secretKey);
        $this->assertEquals(Config::BASE_URL, $mailzeet->baseUrl);
    }

    /**
     * It should construct a MailZeet object in dev mode with custom base url.
     *
     * @test
     */
    public function it_should_construct_a_mailzeet_object_in_dev_mode_with_custom_base_url(): void
    {
        $publicKey = 'public-key';
        $secretKey = 'secret-key';
        $customUrl = 'httpd://custom-url.dev';

        $mailzeet = new MailZeet($publicKey, $secretKey, true, $customUrl);

        $this->assertEquals($customUrl, $mailzeet->baseUrl);
    }

    /**
     * It should throw an exception for empty public key.
     *
     * @test
     */
    public function it_should_thrown_an_exception_for_empty_public_key(): void
    {
        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage('Public key is not set or not a string.');

        new MailZeet('', 'secret-key');
    }

    /**
     * It should throw an exception for empty secret key.
     *
     * @test
     */
    public function it_should_thrown_an_exception_for_empty_secret_key(): void
    {
        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage('Secret key is not set or not a string.');

        new MailZeet('public-key', '');
    }

    /**
     * Payment and Payout classes should extend MailZeet class.
     *
     * @test
     */
    public function payment_and_payout_classes_should_extend_mailzeet_class(): void
    {
        $payment = Payment::class;
        $payout = Payout::class;

        $this->assertTrue(is_subclass_of($payment, MailZeet::class));
        $this->assertTrue(is_subclass_of($payout, MailZeet::class));
    }
}

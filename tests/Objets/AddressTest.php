<?php

namespace MailZeet\Tests\Objets;

use MailZeet\Exceptions\InvalidPayloadException;
use MailZeet\MailZeet;
use MailZeet\Objects\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    /**
     * It should construct a MailZeet object.
     *
     * @test
     */
    public function it_should_properly_sets_recipient_params(): void
    {
        $recipient = (new Address('email@mailersend.com', 'Recipient'))->toArray();

        self::assertEquals('email@mailersend.com', $recipient['email']);
        self::assertEquals('Recipient', $recipient['name']);
    }

    /**
     * It should construct a MailZeet object.
     *
     * @test
     */
    public function it_should__recipient_validates_email(): void
    {
        $this->expectException(InvalidPayloadException::class);

        new Address(
            'fake-email',
            'Recipient'
        );
    }
}

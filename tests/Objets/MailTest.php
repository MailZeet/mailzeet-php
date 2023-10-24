<?php

namespace MailZeet\Tests\Objets;

use MailZeet\Configs\Config;
use MailZeet\Exceptions\InvalidPayloadException;
use MailZeet\MailZeet;
use MailZeet\Objects\Attachment;
use MailZeet\Objects\Mail;
use MailZeet\Objects\Recipient;
use PHPUnit\Framework\TestCase;

class MailTest extends TestCase
{
    /**
    * @test
    */
    public function it_should_set_and_get_reply_to(): void
    {
        $mail = new Mail();
        $mail->setReplyTo([new Recipient('test@example.com', 'Test User')]);

        $replyTo = $mail->getReplyTo();

        self::assertEquals('test@example.com', $replyTo[0]['email']);
        self::assertEquals('Test User', $replyTo[0]['name']);
    }

    /**
     * @test
     */
    public function it_should_set_and_get_recipients(): void
    {
        $mail = new Mail();
        $mail->setRecipients([new Recipient('recipient@example.com', 'Recipient')]);

        $recipients = $mail->getRecipients();

        self::assertEquals('recipient@example.com', $recipients[0]['email']);
        self::assertEquals('Recipient', $recipients[0]['name']);
    }

    /**
     * @test
     */
    public function it_should_set_and_get_cc(): void
    {
        $mail = new Mail();
        $mail->setCc([new Recipient('cc@example.com', 'Cc User')]);

        $cc = $mail->getCc();

        self::assertEquals('cc@example.com', $cc[0]['email']);
        self::assertEquals('Cc User', $cc[0]['name']);
    }

    /**
     * @test
     */
    public function it_should_set_and_get_bcc(): void
    {
        $mail = new Mail();
        $mail->setBcc([new Recipient('bcc@example.com', 'Bcc User')]);

        $bcc = $mail->getBcc();

        self::assertEquals('bcc@example.com', $bcc[0]['email']);
        self::assertEquals('Bcc User', $bcc[0]['name']);
    }

    /**
     * @test
     */
    public function it_should_set_and_get_template_id(): void
    {
        $mail = new Mail();
        $mail->setTemplateId('template-123');

        self::assertEquals('template-123', $mail->getTemplateId());
    }

    /**
     * @test
     */
    public function it_should_set_and_get_subject(): void
    {
        $mail = new Mail();
        $mail->setSubject('Test Subject');

        self::assertEquals('Test Subject', $mail->getSubject());
    }

    /**
     * @test
     */
    public function it_should_set_and_get_html(): void
    {
        $mail = new Mail();
        $mail->setHtml('<h1>Hello</h1>');

        self::assertEquals('<h1>Hello</h1>', $mail->getHtml());
    }

    /**
     * @test
     */
    public function it_should_set_and_get_text(): void
    {
        $mail = new Mail();
        $mail->setText('Hello');

        self::assertEquals('Hello', $mail->getText());
    }

    /**
     * @test
     */
    public function it_should_set_and_get_params(): void
    {
        $mail = new Mail();
        $mail->setParams(['key' => 'value']);

        $params = $mail->getParams();

        self::assertEquals('value', $params['key']);
    }

    /**
     * @test
     */
    public function it_should_set_and_get_attachments(): void
    {
        $attachment = $this->createMock(Attachment::class);
        $attachment->method('toArray')->willReturn(['filename' => 'file.txt']);

        $mail = new Mail();
        $mail->setAttachments([$attachment]);

        $attachments = $mail->getAttachments();

        self::assertEquals('file.txt', $attachments[0]['filename']);
    }

    /**
     * @test
     */
    public function it_should_set_and_get_trackOpens(): void
    {
        $mail = new Mail();
        $mail->setTrackOpens(true);

        self::assertTrue($mail->trackOpens());
    }

    /**
     * @test
     */
    public function it_should_set_and_get_priority(): void
    {
        $mail = new Mail();
        $mail->setPriority(Config::PRIORITY_NORMAL);

        self::assertEquals(Config::PRIORITY_NORMAL, $mail->getPriority());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_for_invalid_cc(): void
    {
        $mail = new Mail();

        $this->expectException(\InvalidArgumentException::class);
        $mail->setCc(['invalid-cc']);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_for_invalid_bcc(): void
    {
        $mail = new Mail();

        $this->expectException(\InvalidArgumentException::class);
        $mail->setBcc(['invalid-bcc']);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_for_invalid_attachments(): void
    {
        $mail = new Mail();

        $this->expectException(\InvalidArgumentException::class);
        $mail->setAttachments(['invalid-attachment']);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_for_invalid_recipient(): void
    {
        $mail = new Mail();

        $this->expectException(\InvalidArgumentException::class);
        $mail->setRecipients(['invalid-recipient']);
    }

    /**
     * @test
     */
    public function it_should_return_mail_as_array(): void
    {
        $mail = new Mail();
        $mail->setSubject('Test');

        $array = $mail->toArray();

        self::assertEquals('Test', $array['subject']);
    }
}

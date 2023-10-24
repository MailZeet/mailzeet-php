<?php

namespace MailZeet\Tests\Objets;

use MailZeet\Objects\Attachment;
use MailZeet\Tests\TestCase;

class AttachmentTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_encode_content_in_base64(): void
    {
        $attachment = new Attachment('test', 'test.txt');

        $data = $attachment->toArray();

        self::assertEquals(base64_encode('test'), $data['content']);
    }

    /**
     * @test
     */
    public function it_should_not_double_encode_base64_content(): void
    {
        $content = base64_encode('test');
        $attachment = new Attachment($content, 'test.txt');

        $data = $attachment->toArray();

        self::assertEquals($content, $data['content']);
    }

    /**
     * @test
     */
    public function it_should_set_filename_correctly(): void
    {
        $filename = 'test.txt';
        $attachment = new Attachment('test', $filename);

        $data = $attachment->toArray();

        self::assertEquals($filename, $data['name']);
    }

}

<?php

namespace MailZeet\Objects;

class Attachment
{
    protected string $content;

    protected string $filename;

    public function __construct(
        string $content,
        string $filename
    ) {
        $this->setContent($content);
        $this->setFilename($filename);
    }

    public function setContent(string $content): void
    {
        if (! $this->isBase64($content)) {
            $this->content = base64_encode($content);
        } else {
            $this->content = $content;
        }
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    protected function isBase64($string): bool
    {
        $decoded_data = base64_decode($string, true);
        $encoded_data = base64_encode($decoded_data);

        return $encoded_data === $string;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'name'    => $this->filename,
        ];
    }

    public static function fromFile(string $path): self
    {
        $content = file_get_contents($path);
        $filename = basename($path);

        return new self($content, $filename);
    }

    public static function fromString(string $content, string $filename): self
    {
        return new self($content, $filename);
    }

    public static function make(string $path, string $filename = null): self
    {
        if (! $filename) {
            $filename = basename($path);
        }

        return self::fromFile($path, $filename);
    }
}

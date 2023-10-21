<?php

namespace MailZeet\Objects;

use MailZeet\Configs\Config;
use MailZeet\Exceptions\InvalidPayloadException;

class MailObject
{
    private ?string $templateId;

    private ?string $to = null;

    private ?string $replyTo = null;

    private array $params = [];

    private int $priority = Config::PRIORITY_NORMAL;

    public function template(string $templateId): self
    {
        $this->templateId = $templateId;

        return $this;
    }

    public function to(string $to): self
    {
        if (filter_var($to, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidPayloadException('Email is not valid.');
        }

        $this->to = $to;

        return $this;
    }

    public function withParams(array $params = []): self
    {
        $this->params = $params;

        return $this;
    }

    public function replyTo(string $replyTo): self
    {
        if (filter_var($replyTo, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidPayloadException('Reply to email is not valid.');
        }

        $this->replyTo = $replyTo;

        return $this;
    }

    public function priority(int $priority): self
    {
        if (! in_array($priority, [Config::PRIORITY_HIGH, Config::PRIORITY_NORMAL], true)) {
            throw new InvalidPayloadException('Priority is not valid. Use "0 - Normal" or "1 - High".');
        }

        $this->priority = $priority;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'template_id'       => $this->templateId,
            'to_email'          => $this->to,
            'params'            => $this->params,
            'priority'          => $this->priority,
            'reply_to_email'    => $this->replyTo,
        ];
    }
}

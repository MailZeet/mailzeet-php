<?php

namespace MailZeet\Objects;

use MailZeet\Configs\Config;

/**
 * Class MailObject.
 *
 * Represents a structured email object that's intended to be sent using the MailZeet service.
 */
class Mail
{
    protected array $reply_to = [];

    protected array $recipients = [];

    protected array $cc = [];

    protected array $bcc = [];

    protected ?string $template_id = null;

    protected array $attachments = [];

    protected array $params = [];

    protected bool $trackOpens = true;

    protected ?string $subject = null;

    protected ?string $html = null;

    protected ?string $text = null;

    protected int $priority = Config::PRIORITY_NORMAL;

    public function getReplyTo(): array
    {
        return $this->reply_to;
    }

    public function setReplyTo(array $reply_to): Mail
    {
        $this->reply_to = $this->mapToArray($reply_to, Recipient::class);

        return $this;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setRecipients(array $recipients): Mail
    {
        $this->recipients = $this->mapToArray($recipients, Recipient::class);

        return $this;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function setCc(array $cc): Mail
    {
        $this->cc = $this->mapToArray($cc, Recipient::class);

        return $this;
    }

    public function getBcc(): ?array
    {
        return $this->bcc ?? [];
    }

    public function setBcc(array $bcc): Mail
    {
        $this->bcc = $this->mapToArray($bcc, Recipient::class);
        return $this;
    }

    public function getTemplateId(): ?string
    {
        return $this->template_id;
    }

    public function setTemplateId(string $template_id): Mail
    {
        $this->template_id = $template_id;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): Mail
    {
        $this->subject = $subject;
        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): Mail
    {
        $this->html = $html;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): Mail
    {
        $this->text = $text;
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $variables): Mail
    {
        $this->params = $variables;
        return $this;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function setAttachments(array $attachments): Mail
    {
        $this->attachments = $this->mapToArray($attachments, Attachment::class);
        return $this;
    }

    public function trackOpens(): ?bool
    {
        return $this->trackOpens;
    }

    public function setTrackOpens(bool $trackOpens): self
    {
        $this->trackOpens = $trackOpens;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    private function mapToArray(array $data, string $objectClass): array
    {
        $array = [];

        foreach ($data as $item) {
            if (! $item instanceof $objectClass) {
                throw new \InvalidArgumentException(sprintf('Item must be an instance of %s', $objectClass));
            }

            $array[] = $item->toArray();
        }

        return $array;
    }

    public static function make(): self
    {
        return new self();
    }

    public function toArray(): array
    {
        return [
            'reply_to'     => $this->getReplyTo(),
            'recipients'   => $this->getRecipients(),
            'cc'           => $this->getCc(),
            'bcc'          => $this->getBcc(),
            'template_id'  => $this->getTemplateId(),
            'text_content' => $this->getText(),
            'html_content' => $this->getHtml(),
            'subject'      => $this->getSubject(),
            'params'       => $this->getParams(),
            'attachments'  => $this->getAttachments(),
            'track_opens'  => $this->trackOpens(),
            'priority'     => $this->getPriority(),
        ];
    }
}

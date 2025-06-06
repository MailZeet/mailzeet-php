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
    protected array $replyTo = [];

    protected array $recipients = [];

    protected array $cc = [];

    protected array $bcc = [];

    protected ?string $templateId = null;

    protected array $attachments = [];

    protected array $params = [];

    protected bool $trackOpens = true;

    protected ?string $subject = null;

    protected ?string $html = null;

    protected ?string $text = null;

    protected ?string $language = null;

    protected int $priority = Config::PRIORITY_NORMAL;

    private ?Address $sender = null;

    public function getSender(): ?Address
    {
        return $this->sender;
    }

    public function setSender(?Address $sender): Mail
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReplyTo(): array
    {
        return $this->replyTo;
    }

    public function setReplyTo($replyTo): Mail
    {
        $replyTo = is_array($replyTo) ? $replyTo : [$replyTo];

        $this->replyTo = $this->mapToArray($replyTo, Address::class);

        return $this;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setRecipients($recipients): Mail
    {
        $recipients = is_array($recipients) ? $recipients : [$recipients];

        $this->recipients = $this->mapToArray($recipients, Address::class);

        return $this;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function setCc($cc): Mail
    {
        $cc = is_array($cc) ? $cc : [$cc];

        $this->cc = $this->mapToArray($cc, Address::class);

        return $this;
    }

    public function getBcc(): ?array
    {
        return $this->bcc ?? [];
    }

    public function setBcc($bcc): Mail
    {
        $bcc = is_array($bcc) ? $bcc : [$bcc];

        $this->bcc = $this->mapToArray($bcc, Address::class);

        return $this;
    }

    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    public function setTemplateId(string $templateId): Mail
    {
        $this->templateId = $templateId;

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

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
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
            'text'         => $this->getText(),
            'html'         => $this->getHtml(),
            'subject'      => $this->getSubject(),
            'params'       => $this->getParams(),
            'track_opens'  => $this->trackOpens(),
            'sender'       => $this->getSender() ? $this->getSender()->toArray() : null,
            'priority'     => $this->getPriority(),
            'language'     => $this->getLanguage(),
        ];
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
}

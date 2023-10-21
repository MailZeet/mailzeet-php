<?php

namespace MailZeet\Objects;

use MailZeet\Configs\Config;
use MailZeet\Exceptions\InvalidPayloadException;

/**
 * Class MailObject.
 *
 * Represents a structured email object that's intended to be sent using the MailZeet service.
 */
class MailObject
{
    /**
     * @var string|null - The ID of the email template to be used
     */
    private ?string $templateId;

    /**
     * @var string|null - The primary recipient of the email
     */
    private ?string $to = null;

    /**
     * @var string|null - The email address that should receive replies to this message
     */
    private ?string $replyTo = null;

    /**
     * @var array - Parameters to customize the email content based on the selected template
     */
    private array $params = [];

    /**
     * @var int - The priority of the email. Use constants from Config class for setting values.
     */
    private int $priority = Config::PRIORITY_NORMAL;

    /**
     * Set the template ID.
     *
     * @param string $templateId - ID of the email template
     *
     * @return self - Returns the current instance
     */
    public function template(string $templateId): self
    {
        $this->templateId = $templateId;
        return $this;
    }

    /**
     * Set the recipient of the email.
     *
     * @param string $to - Recipient's email address
     *
     * @throws invalidPayloadException - If the provided email is not valid
     *
     * @return self - Returns the current instance
     */
    public function to(string $to): self
    {
        if (filter_var($to, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidPayloadException('Email is not valid.');
        }
        $this->to = $to;
        return $this;
    }

    /**
     * Set the parameters for the email template.
     *
     * @param array $params - Parameters to replace placeholders in the email template
     *
     * @return self - Returns the current instance
     */
    public function withParams(array $params = []): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Set the reply-to address for the email.
     *
     * @param string $replyTo - Email address for replies
     *
     * @throws invalidPayloadException - If the provided reply-to email is not valid
     *
     * @return self - Returns the current instance
     */
    public function replyTo(string $replyTo): self
    {
        if (filter_var($replyTo, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidPayloadException('Reply to email is not valid.');
        }
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * Set the priority of the email.
     *
     * @param int $priority - Email priority level. (Available : HIGH -> 1, NORMAL -> 0)
     *
     * @throws invalidPayloadException - If an invalid priority value is provided
     *
     * @return self - Returns the current instance
     */
    public function priority(int $priority): self
    {
        if (! in_array($priority, [Config::PRIORITY_HIGH, Config::PRIORITY_NORMAL], true)) {
            throw new InvalidPayloadException('Priority is not valid. Use "0 - Normal" or "1 - High".');
        }
        $this->priority = $priority;
        return $this;
    }

    /**
     * Convert the MailObject to an array format suitable for API consumption.
     *
     * @return array the MailObject in array format
     */
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

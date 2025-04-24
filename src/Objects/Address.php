<?php

namespace MailZeet\Objects;

class Address
{
    protected string $name;

    protected string $email;

    public function __construct(string $email, string $name = '')
    {
        $this->setEmail($email);
        $this->setName($name);
    }

    public function toArray(): array
    {
        return [
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }

    public static function make(string $email, string $name = ''): self
    {
        return new self($email, $name);
    }

    private function setName(?string $name): void
    {
        $this->name = $name;
    }

    private function setEmail(string $email): void
    {
        $this->email = $email;
    }
}

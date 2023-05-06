<?php

namespace Inbenta\Domain\Entity\Chat;

class Response
{
    public function __construct(private readonly string $message, private readonly ?string $flag = null)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFlag(): ?string
    {
        return $this->flag ?? null;
    }

    public function toArray(): array
    {
        return ['message' => $this->message, 'flag' => $this->flag];
    }

}
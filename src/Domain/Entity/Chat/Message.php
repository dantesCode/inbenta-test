<?php

namespace Inbenta\Domain\Entity\Chat;

use DateTime;

class Message
{

    public function __construct(private int $createdDate, private string $who, private ?string $flag = null, private string $text)
    {
    }

    public function getCreatedDate(): int
    {
        return $this->createdDate;
    }

    public function getWho(): string
    {
        return $this->who;
    }

    public function getFlag(): ?string
    {
        return $this->flag ?? null;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
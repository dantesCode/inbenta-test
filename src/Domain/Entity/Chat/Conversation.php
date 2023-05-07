<?php

namespace Inbenta\Domain\Entity\Chat;

use Inbenta\Domain\Shared\Aggregate\AggregateRoot;

class Conversation extends AggregateRoot
{

    public function __construct(private string $id, private array $messages)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }
}
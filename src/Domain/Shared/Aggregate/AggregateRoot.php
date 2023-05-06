<?php

declare(strict_types=1);

namespace Inbenta\Domain\Shared\Aggregate;

abstract class AggregateRoot
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    protected function recordThat(DomainEvent $domainEvent): void
    {
        $this->events[] = $domainEvent;
    }

    /**
     * @return DomainEvent[]
     */
    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}

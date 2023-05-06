<?php

declare(strict_types=1);

namespace Inbenta\Domain\Shared\Aggregate;

use DateTimeInterface;

interface DomainEvent
{
    public function occurredOn(): DateTimeInterface;

    public function normalize(): array;

    public static function denormalize(array $payload): self;
}

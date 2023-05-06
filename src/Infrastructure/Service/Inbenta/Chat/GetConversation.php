<?php

namespace Inbenta\Infrastructure\Service\Inbenta\Chat;

use Symfony\Component\HttpFoundation\RequestStack;

final class GetConversation implements \Inbenta\Domain\Service\Chat\GetConversation
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function __invoke(): array
    {
        return $this->requestStack->getSession()->get('conversation', []);
    }
}
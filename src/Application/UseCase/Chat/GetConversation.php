<?php

namespace Inbenta\Application\UseCase\Chat;

use Inbenta\Domain\Service\Chat\GetConversation as GetConversationService;

class GetConversation
{

    public function __construct(private readonly GetConversationService $getConversation)
    {
    }

    public function __invoke(): array
    {
        return $this->getConversation->__invoke();
    }
}
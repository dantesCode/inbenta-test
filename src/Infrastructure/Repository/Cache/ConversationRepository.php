<?php

namespace Inbenta\Infrastructure\Repository\Cache;

use Inbenta\Domain\Entity\Chat\Conversation;
use Symfony\Component\HttpFoundation\RequestStack;
use Inbenta\Domain\Repository\ConversationRepository as ConversationRepositoryInterface;

class ConversationRepository implements ConversationRepositoryInterface
{

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getCurrentConversation()
    {
        return $this->requestStack->getSession()->get('conversation', []);
    }

    public function getBotMessages(): array
    {
        $botMessages = [];
        $conversation = $this->getCurrentConversation();
        foreach ($conversation->getMessages() as $message) {
            if ($message->getWho() === 'bot') {
               $botMessages[] = $message;
            }
        }

        return $botMessages;
    }

    public function save($conversation): bool
    {
        $this->requestStack->getSession()->set('conversation', $conversation);

        return true;
    }
}
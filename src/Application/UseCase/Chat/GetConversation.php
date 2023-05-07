<?php

namespace Inbenta\Application\UseCase\Chat;

use Inbenta\Domain\Entity\Chat\Conversation;
use Inbenta\Domain\Repository\ConversationRepository;
use Inbenta\Domain\Service\Chat\GetConversation as GetConversationService;

class GetConversation
{

    public function __construct(private readonly ConversationRepository $conversationRepository)
    {
    }

    public function __invoke(): array
    {
        $conversationMessages = [];
        $conversation = $this->conversationRepository->getCurrentConversation();

        if (!empty($conversation)) {
            $messages = $conversation->getMessages();

            foreach ($messages as $message) {
                $conversationMessages[] = [$message->getWho(), $message->getText()];
            }
        }

        return $conversationMessages;
    }
}
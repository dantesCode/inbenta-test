<?php

namespace Inbenta\Domain\Repository;

use Inbenta\Domain\Entity\Chat\Conversation;

interface ConversationRepository
{
    public function getCurrentConversation();

    public function save($conversation): bool;
}
<?php

declare(strict_types=1);

namespace Inbenta\Infrastructure\Symfony\Http;

use Inbenta\Application\UseCase\Chat\GetConversation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatGetConversationController
{
    public function __construct(private readonly GetConversation $conversation) {
    }

    #[Route('/chat/messages', name: 'messages', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $conversation = $this->conversation->__invoke();

        return new JsonResponse($conversation);
    }
}

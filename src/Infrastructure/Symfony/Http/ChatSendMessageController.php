<?php

declare(strict_types=1);

namespace Inbenta\Infrastructure\Symfony\Http;

use Inbenta\Application\UseCase\Chat\SendMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatSendMessageController
{
    public function __construct(private readonly SendMessage $start) {
    }

    #[Route('/chat/message', name: 'message', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $message = $request->get('message');
        $conversation = $this->start->__invoke($message);
        return new JsonResponse($conversation);
    }
}

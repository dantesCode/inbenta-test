<?php

namespace Inbenta\Application\UseCase\Chat;

use Inbenta\Domain\Service\GetActors;
use Inbenta\Domain\Service\GetMovies;
use Inbenta\Domain\Entity\Chat\Response;
use Inbenta\Domain\Service\Chat\SendMessage as SendMessageService;

class SendMessage
{

    public function __construct(private readonly SendMessageService $sendMessage, private readonly GetActors $actors, private readonly GetMovies $movies)
    {
    }

    public function __invoke(string $message): array
    {
        if (str_contains(strtolower($message), 'force')) {
            return (new Response(implode('\n ', $this->movies->__invoke())))->toArray();
        }

        $conversation = $this->sendMessage->__invoke($message);

        $lastMessage = end($conversation);
        $responseMessage = new Response($lastMessage['message'], $lastMessage['flag']);

        $lastTwoMessages = array_slice($conversation, -2, 2);

        $noResults = true;

        foreach ($lastTwoMessages as $messageData) {
            if ($messageData['flag'] !== 'no-results') {
                $noResults = false;
                break;
            }
        }

        if ($noResults) {
            return (new Response(implode('\n ', $this->actors->__invoke())))->toArray();
        }

        return $responseMessage->toArray();
    }
}
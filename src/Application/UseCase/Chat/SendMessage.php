<?php

namespace Inbenta\Application\UseCase\Chat;

use DateTimeImmutable;
use Inbenta\Domain\Entity\Chat\Conversation;
use Inbenta\Domain\Entity\Chat\Message;
use Inbenta\Domain\Service\GetActors;
use Inbenta\Domain\Service\GetMovies;
use Inbenta\Domain\Entity\Chat\Response;
use Inbenta\Domain\Service\Chat\SendMessage as SendMessageService;
use Inbenta\Infrastructure\Repository\Cache\ConversationRepository;
use Inbenta\Infrastructure\Service\Inbenta\Chat\NewSession as NewConversation;

class SendMessage
{
    private int $date;

    public function __construct(
        private readonly SendMessageService $sendMessage,
        private readonly GetActors $actors,
        private readonly GetMovies $movies,
        private readonly NewConversation $newConversation,
        private ConversationRepository $conversationRepository
    ) {
        $date = new DateTimeImmutable();
        $this->date = $date->getTimestamp();
    }

    public function __invoke(string $message): array
    {
        $conversation =
            !empty($this->conversationRepository->getCurrentConversation())
                ? $this->conversationRepository->getCurrentConversation()
                : $this->createNewConversation();

        $this->addUserMessage($conversation, $message);

        if (str_contains(strtolower($message), 'force')) {
            $this->addBotMessage($conversation, json_encode($this->movies->__invoke()));
            $this->saveConversation($conversation);
            return (new Response(json_encode($this->movies->__invoke())))->toArray();
        }

        $botResponse = $this->sendMessage->__invoke($message);
        $this->addBotMessage($conversation, $botResponse['message'], $botResponse['flag']);
        $this->saveConversation($conversation);

        if ($this->shouldReturnActors()) {
            return (new Response(json_encode($this->actors->__invoke())))->toArray();
        }

        return (new Response($botResponse['message'], $botResponse['flag']))->toArray();
    }

    private function createNewConversation(): Conversation
    {
        $conversationId = $this->newConversation->__invoke();
        return new Conversation($conversationId, []);
    }

    private function addUserMessage(Conversation $conversation, string $message): void
    {
        $messages = $conversation->getMessages();
        $messages[] = new Message($this->date, 'me', '', $message);
        $conversation->setMessages($messages);
    }

    private function addBotMessage(Conversation $conversation, string $message, ?string $flag = null): void
    {
        $messages = $conversation->getMessages();
        $messages[] = new Message($this->date, 'bot', $flag, $message);
        $conversation->setMessages($messages);
    }

    private function saveConversation(Conversation $conversation): void
    {
        $this->conversationRepository->save($conversation);
    }

    private function shouldReturnActors(): bool
    {
        $botMessages = $this->conversationRepository->getBotMessages();
        $lastTwoMessages = array_slice($botMessages, -2, 2);
        $noResults = true;

        foreach ($lastTwoMessages as $messageData) {
            if ($messageData->getFlag() !== 'no-results') {
                $noResults = false;
                break;
            }
        }

        return $noResults && count($lastTwoMessages) === 2;
    }

}
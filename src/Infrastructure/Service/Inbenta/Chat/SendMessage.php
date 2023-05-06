<?php

namespace Inbenta\Infrastructure\Service\Inbenta\Chat;

use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Inbenta\Infrastructure\Model\Inbenta\Request\Request;
use Inbenta\Infrastructure\Model\Inbenta\Request\RequestFactory;

final class SendMessage implements \Inbenta\Domain\Service\Chat\SendMessage
{
    private Client $client;
    private Request $request;

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly GetConversation $getConversation,
        private readonly GetSessionToken $getSessionToken,
        private readonly RequestStack $requestStack,
        private readonly string $apiKey
    ) {
        $this->client = new Client();
        $this->request = $this->requestFactory->build();
    }

    public function __invoke($message): array
    {
        $sessionToken = $this->getSessionToken();
        $conversation = $this->getConversation();

        $response = $this->client->request('POST', $this->request->getBaseUrl() . '/conversation/message', [
            'headers' =>
                [
                    'x-inbenta-key'     => $this->apiKey,
                    'Authorization'     => 'Bearer ' . $this->request->getToken(),
                    'x-inbenta-session' => 'Bearer ' . $sessionToken
                ],
            'body'    => json_encode(
                [
                    'message' => $message
                ]
            )
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new Exception();
        }

        $responseData = json_decode($response->getBody()->getContents());
        $responseMessage = $responseData->answers[0]->message ?? '';
        $responseFlag = !empty($responseData->answers[0]->flags) ? $responseData->answers[0]->flags[0] : null;

        return $this->addMessageToConversation($responseMessage, $responseFlag, $conversation);
    }

    private function getConversation(): array
    {
        return $this->getConversation->__invoke();
    }

    private function getSessionToken()
    {
        return $this->getSessionToken->__invoke();
    }

    private function addMessageToConversation(
        string $message,
        ?string $flag,
        array $conversation
    ): array {
        $conversation[] =
            [
                'message' => $message,
                'flag'    => $flag
            ];

        //set conversation on session
        $this->requestStack->getSession()->set('conversation', $conversation);

        return $conversation;
    }
}
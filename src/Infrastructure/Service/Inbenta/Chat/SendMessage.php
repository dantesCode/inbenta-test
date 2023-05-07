<?php

namespace Inbenta\Infrastructure\Service\Inbenta\Chat;

use Exception;
use GuzzleHttp\Client;
use Inbenta\Infrastructure\Model\Inbenta\Request;
use Inbenta\Infrastructure\Model\Inbenta\RequestFactory;
use Symfony\Component\HttpFoundation\Response;

final class SendMessage implements \Inbenta\Domain\Service\Chat\SendMessage
{
    private Client $client;
    private Request $request;

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly GetSessionToken $getSessionToken,
        private readonly string $apiKey
    ) {
        $this->client = new Client();
        $this->request = $this->requestFactory->build();
    }

    public function __invoke($message): array
    {
        $sessionToken = $this->getSessionToken();

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

        return ['message' => $responseMessage, 'flag' => $responseFlag];
    }

    private function getSessionToken()
    {
        return $this->getSessionToken->__invoke();
    }
}
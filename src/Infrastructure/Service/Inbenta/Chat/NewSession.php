<?php

namespace Inbenta\Infrastructure\Service\Inbenta\Chat;

use Exception;
use GuzzleHttp\Client;
use Inbenta\Infrastructure\Model\Inbenta\Request\Request;
use Inbenta\Infrastructure\Model\Inbenta\Request\RequestFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class NewSession
{
    private Client $client;
    private Request $request;

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly RequestStack $requestStack,
        private readonly string $apiKey
    ) {
        $this->client = new Client();
        $this->request = $this->requestFactory->build();
    }

    public function __invoke()
    {
        $response = $this->client->request('POST', $this->request->getBaseUrl() . '/conversation', [
            'headers' =>
                [
                    'x-inbenta-key' => $this->apiKey,
                    'Authorization' => 'Bearer ' . $this->request->getToken()
                ]
        ]);

        $responseData = json_decode($response->getBody()->getContents());

        $this->requestStack->getSession()->set('conv-session-token', $responseData->sessionToken);
        $this->requestStack->getSession()->set('conv-session-id', $responseData->sessionId);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new Exception();
        }

        return $responseData->sessionToken;
    }
}
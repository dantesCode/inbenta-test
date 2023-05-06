<?php

namespace Inbenta\Infrastructure\Service\Inbenta\URL;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Inbenta\Infrastructure\Service\Inbenta\Authentication\Token;

class Chatbot
{
    private Client $client;

    public function __construct(
        private readonly Token $token,
        private readonly RequestStack $requestStack,
        private $apiKey
    ) {
        $this->client = new Client();
    }

    public function __invoke()
    {
        $token = $this->requestStack->getSession()->get('token');

        if (!$token) {
            $token = $this->token->__invoke($this->requestStack->getSession());
        }

        $response = $this->client->request('GET', 'https://api.inbenta.io/v1/apis', [
            'headers' =>
                [
                    'x-inbenta-key' => $this->apiKey,
                    'Authorization' => 'Bearer ' . $token
                ]
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new Exception();
        }

        $url = json_decode($response->getBody()->getContents())->apis->chatbot;

        $this->requestStack->getSession()->set('chatbot-url', $url . '/v1');

        return $url . '/v1';
    }
}
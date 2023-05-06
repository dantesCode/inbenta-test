<?php

namespace Inbenta\Infrastructure\Service\Inbenta\Authentication;


use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

final class Token
{
    private Client $client;

    public function __construct(private string $apiKey, private string $secret)
    {
        $this->client = new Client();
    }

    public function __invoke($session)
    {
        $response = $this->client->request('POST', 'https://api.inbenta.io/v1/auth', [
            'headers' =>
                [
                    'x-inbenta-key' => $this->apiKey,
                    'Content-Type'  => 'application/json'
                ],
            'body' => json_encode(['secret' => $this->secret])
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new Exception();
        }

        $token = json_decode($response->getBody()->getContents())->accessToken;

        $session->set('token', $token);

        return $token;
    }
}
<?php

namespace Inbenta\Infrastructure\Service\Inbenta\Chat;

use GuzzleHttp\Client;
use Inbenta\Domain\Service\GetActors as GetActorsInterface;

final class GetActors implements GetActorsInterface
{
    private Client $client;

    public function __construct(private readonly string $url)
    {
        $this->client = new Client();
    }

    public function __invoke(): array
    {
        $response = $this->client->request('GET', $this->url);

        return json_decode($response->getBody()->getContents());
    }
}
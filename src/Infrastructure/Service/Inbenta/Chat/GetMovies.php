<?php

namespace Inbenta\Infrastructure\Service\Inbenta\Chat;

use GuzzleHttp\Client;
use Inbenta\Domain\Service\GetMovies as GetMoviesInterface;

final class GetMovies implements GetMoviesInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function __invoke(): array
    {
        $response = $this->client->request('GET', 'https://star-wars-api-prod.e01.inbenta.services/films');

        return json_decode($response->getBody()->getContents());
    }
}
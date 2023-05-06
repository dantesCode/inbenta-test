<?php

namespace Inbenta\Infrastructure\Model\Inbenta\Request;

class Request
{

    public function __construct(private readonly string $token, private readonly string $baseUrl)
    {
    }


    public function getToken(): string
    {
        return $this->token;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
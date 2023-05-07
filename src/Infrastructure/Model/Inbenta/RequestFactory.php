<?php

namespace Inbenta\Infrastructure\Model\Inbenta;

use Inbenta\Infrastructure\Service\Inbenta\Authentication\Token;
use Inbenta\Infrastructure\Service\Inbenta\URL\Chatbot;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestFactory
{

    public function __construct(private Token $token, private Chatbot $chatbotUrl, private RequestStack $requestStack)
    {
    }

    public function build(): Request
    {
        return new Request($this->getToken(), $this->getBaseUrl());
    }

    private function getToken(): string
    {
        $token = $this->requestStack->getSession()->get('token');

        if (!$token) {
            $token = $this->token->__invoke($this->requestStack->getSession());
        }

        return $token;
    }

    private function getBaseUrl(): string
    {
        $baseUrl = $this->requestStack->getSession()->get('chatbot-url');

        if (!$baseUrl) {
            $baseUrl = $this->chatbotUrl->__invoke($this->requestStack->getSession());
        }

        return $baseUrl;
    }
}
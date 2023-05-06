<?php

namespace Inbenta\Infrastructure\Service\Inbenta\Chat;

use Symfony\Component\HttpFoundation\RequestStack;

class GetSessionToken
{

    public function __construct(private readonly NewSession $newSession, private readonly RequestStack $requestStack)
    {
    }

    public function __invoke()
    {
        $chatSessionToken = $this->requestStack->getSession()->get('conv-session-token');

        if (!$chatSessionToken) {
            $chatSessionToken = $this->newSession->__invoke();
        }

        return $chatSessionToken;
    }


}
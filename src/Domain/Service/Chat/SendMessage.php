<?php

namespace Inbenta\Domain\Service\Chat;

interface SendMessage
{
    public function __invoke($message): array;
}
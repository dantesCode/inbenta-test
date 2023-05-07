<?php

namespace Inbenta\Tests\Unit\Infrastructure\Symfony\Http;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChatGetConversationControllerTest extends WebTestCase
{
    public function testSimpleCall(): void
    {
        $client = static::createClient([], ['session.storage' => null]);

        $client->request(
            'POST',
            '/chat/message',
            ['message' => 'hello']
        );

        $this->assertResponseIsSuccessful();
    }

    public function testTwoConsecutiveNotfound(): void
    {
        $client = static::createClient([], ['session.storage' => null]);

        $client->request(
            'POST',
            '/chat/message',
            ['message' => 'eggs']
        );

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"flag":"no-results"', $client->getResponse()->getContent());

        $client->request(
            'POST',
            '/chat/message',
            ['message' => 'cheese']
        );

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Hayden Christensen', $client->getResponse()->getContent());
    }

    public function testForceStringFound(): void
    {
        $client = static::createClient([], ['session.storage' => null]);

        $client->request(
            'POST',
            '/chat/message',
            ['message' => 'hello force test']
        );

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Star Wars: The Force Awakens', $client->getResponse()->getContent());
    }
}
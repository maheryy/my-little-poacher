<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Event;

class EventsTest extends ApiTestCase
{
    public function testGetCollection() {
        $response = static::createClient()->request('GET', '/events');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Event',
            '@id' => '/events',
            '@type' => 'hydra:Collection',
        ]);
        $this->assertMatchesResourceCollectionJsonSchema(Event::class);
    }
}

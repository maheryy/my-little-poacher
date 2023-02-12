<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Bid;
use App\Entity\Ticket;

class OutBidTest extends ApiTestCase
{
    public function testLogin(): void
    {
        $response = static::createClient()->request('POST', '/auth', [
            'json' => [
                'email' => 'client0@gmail.com',
                'password' => 'password',
            ],
        ]);

        $GLOBALS['token'] = $response->toArray()['token'];
        $this->assertResponseIsSuccessful();
    }

    public function testGetBids(): void
    {
        $response = static::createClient()->request('GET', '/bids', ['auth_bearer' => $GLOBALS['token'],]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Bid',
            '@id' => '/bids',
            '@type' => 'hydra:Collection',
        ]);
    }

    public function testGetBid(): void
    {
        $response = static::createClient()->request('GET', '/bids/6', ['auth_bearer' => $GLOBALS['token'],]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Bid',
            '@id' => '/bids/6',
            '@type' => 'Bid',
        ]);
    }

    public function testPostOutbid(): void
    {
        $response = static::createClient()->request('PATCH', '/bids/6', [
            'auth_bearer' => $GLOBALS['token'],
            'json' => [
                'price' => 200
            ],
        ]);

        $this->assertResponseIsSuccessful();
    }
}
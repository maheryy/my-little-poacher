<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class LoginTest extends ApiTestCase
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
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }
}
<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Tests\AppBundle\DatabasePrimer;

class VideoControllerTest extends ApiTestCase
{
    private string $url = '/api/videos';

    public function testIndex(): void
    {
        $response = static::createClient()->request('GET', $this->url);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();
    }

    public function testShow(): void
    {
        $response = static::createClient()->request('GET', $this->url . '/1');
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();
    }

    public function testStore(): void
    {
        $response = static::createClient()->request('POST', $this->url);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();
    }

    public function testPut(): void
    {
        $response = static::createClient()->request('POST', $this->url);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();
    }

    public function testDelete(): void
    {
        $response = static::createClient()->request('POST', $this->url . '/1');
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();
    }
}

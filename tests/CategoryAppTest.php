<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CategoryAppTest extends ApiTestCase
{
    private string $defaultUrl = '/api/categories';

    public function testResponseMustListAllCategoriesSuccefully(): void
    {
        $client = static::createClient();
        $client->request('GET', $this->defaultUrl);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testResponseMustCreateACategorieSuccefully()
    {
        $client = static::createClient();
        $response = $client->request(
            'POST', 
            $this->defaultUrl, 
            
            [],

        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

}

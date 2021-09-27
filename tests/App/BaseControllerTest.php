<?php

namespace App\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Category;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class BaseControllerTest extends ApiTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
    
    /**
     * @dataProvider resources
     */
    public function testRequestMustCreateAnEntitySuccessfully($resource, $data)
    {
        $this->client->request(
            'POST', 
            '/api/' . $resource,
            ['body' => $data]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    /**
     * @dataProvider resources
     */
    public function testRequestMustListAllResourcesSuccessfully($resource)
    {
        $this->client->request('GET', '/api/' . $resource);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider resources
     */
    public function testRequestMustFetchOneResourceSuccessfully($resource)
    {
        $this->client->request('GET', '/api/' . $resource . '/100');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider resources
     */
    public function testRequestMustUpdateAResourceSuccessfully($resource, $data)
    {
        $this->client->request(
            'PUT', 
            '/api/' . $resource . '/100',
            ['body' => $data]
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider resources
     */
    public function testRequestMustDeleteAResourceSuccessfully($resource)
    {
        $this->client->request(
            'DELETE', 
            '/api/' . $resource . '/100',
        );

        $this->assertResponseStatusCodeSame(410);
    }

    public function resources()
    {

        $categoryData = [
            'title' => 'category title', 
            'color' => 'category color'
        ];
        $videoData = [
            'title' => 'title', 
            'description' => 'description', 
            'url' => 'http://url.com', 
            'category_id' => 100
        ];

        $categoryDataEncoded = json_encode($categoryData);
        $videoDataEncoded = json_encode($videoData);

        return [
            'category' => ['categories', $categoryDataEncoded],
            'video' => ['videos', $videoDataEncoded]
        ];
    }

    protected function tearDown(): void
    {
        $this->client = null;
    }
}
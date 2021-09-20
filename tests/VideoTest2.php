<?php

namespace App\Tests;

use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VideoTest extends KernelTestCase
{
    private $entityManager;
    private $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $getDoctrine = $kernel->getContainer()->get('doctrine');
        $this->entityManager = $getDoctrine->getManager();
        $this->repository = $getDoctrine->getRepository(Video::class);

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
    
    public function testMustStoreAVideoInDatabase(): void
    {
        $video = new Video('First title', 'First description', 'First url');
        $this->entityManager->persist($video);
        $this->entityManager->flush();
        
        $id = $video->getId();
        $video = $this->repository->find($id);

        $this->assertEquals('First title', $video->getTitle());
        $this->assertEquals('First description', $video->getDescription());
        $this->assertEquals('First url', $video->getUrl());
    }

    public function testMustUpdateThePropertyValuesOfAVideo()
    {
        $video = new Video('First title', 'First description', 'First url');
        $this->entityManager->persist($video);
        $this->entityManager->flush();

        $videoId = $video->getId();
        $videoRecord = $this->repository->find($videoId);
        $title = 'New title';
        $description = 'New description';
        $url = 'New url';
        $videoRecord->updatePropertiesValues($title, $description, $url);
        $this->entityManager->flush();

        $this->assertEquals('New title', $video->getTitle());
        $this->assertEquals('New description', $video->getDescription());
        $this->assertEquals('New url', $video->getUrl());
    }
}

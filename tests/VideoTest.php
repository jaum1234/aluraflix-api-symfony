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
        $video = new Video();
        $video->setTitle('First test video');
        $video->setDescription('Its description');
        $video->setUrl('Its url');

        $this->entityManager->persist($video);
        $this->entityManager->flush();
        
        $video = $this->repository->findOneBy(['title' => 'First test video']);

        $this->assertEquals('First test video', $video->getTitle());
        $this->assertEquals('Its description', $video->getDescription());
        $this->assertEquals('Its url', $video->getUrl());
    }

    public function testMustFetchAllVideosInIdOrder()
    {
        $video = new Video();
        $video->setTitle('First test video');
        $video->setDescription('Its description');
        $video->setUrl('Its url');

        $video2 = new Video();
        $video2->setTitle('Second test video');
        $video2->setDescription('Its description');
        $video2->setUrl('Its url');

        $video3 = new Video();
        $video3->setTitle('Third test video');
        $video3->setDescription('Its description');
        $video3->setUrl('Its url');

        $videos = $this->repository->findAll();
        $videoArray = (array) $videos;
        
        
    }
}

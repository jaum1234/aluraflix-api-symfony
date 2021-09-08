<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Video;
use App\Repository\VideoRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VideoRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
        ->get('doctrine')
        ->getManager();

    }
    
    public function testMustlistAllVideos(): void
    {
        //Arrange
        $category = new Category('title', 'color');
        $video1 = new Video('title', 'description', 'http://url.com', $category);
        $video2 = new Video('title2', 'description2', 'http://url2.com', $category);
    
        $this->entityManager->persist($category);
        $this->entityManager->persist($video1);
        $this->entityManager->persist($video2);
        $this->entityManager->flush();

        //Act
        $videoRepository = $this->entityManager->getRepository(Video::class);
        $videos = $videoRepository->findAll();

        //Assert
        $this->assertEquals('title', $videos[0]->getTitle());
        $this->assertEquals('description', $videos[0]->getDescription());
        $this->assertEquals('http://url.com', $videos[0]->getUrl());
        $this->assertEquals($category, $videos[0]->getCategory());

        $this->assertEquals('title2', $videos[1]->getTitle());
        $this->assertEquals('description2', $videos[1]->getDescription());
        $this->assertEquals('http://url2.com', $videos[1]->getUrl());
        $this->assertEquals($category, $videos[1]->getCategory());
    }

    public function testMustFetchOneVideo()
    {
        //Arrange
        $category = new Category('title', 'color');
        $video = new Video('title', 'description', 'http://url.com', $category);
        $this->entityManager->persist($category);
        $this->entityManager->persist($video);
        $this->entityManager->flush();
        $id = $video->getId();

        //Act
        $videoRepository = $this->entityManager->getRepository(Video::class);
        $video = $videoRepository->find($id);

        //Assert
        $this->assertEquals('title', $video->getTitle());
        $this->assertEquals('description', $video->getDescription());
        $this->assertEquals('http://url.com', $video->getUrl());
        $this->assertEquals($category, $video->getCategory());
    }

    /**
     * @dataProvider dataForQueringByQueryParameter
     */
    public function testMustFetchVideosByQueryParameter(string $queryParameter, string $title, string $description, string $url, Category $category)
    {
        //Arrange
        $video1 = new Video('title', 'description', 'http://url.com', $category);
        $video2 = new Video('title2', 'description2', 'http://url2.com', $category);
    
        $this->entityManager->persist($category);
        $this->entityManager->persist($video1);
        $this->entityManager->persist($video2);
        $this->entityManager->flush();

        //act
        $videoRepository = $this->entityManager->getRepository(Video::class);
        $video = $videoRepository->findByQueryParameter($queryParameter);

        //assert
        $this->assertEquals($title, $video[0]->getTitle());
        $this->assertEquals($description, $video[0]->getDescription());
        $this->assertEquals($url, $video[0]->getUrl());
        $this->assertEquals($category, $video[0]->getCategory());
    }

    public function dataForQueringByQueryParameter()
    {
        $category = new Category('title', 'color');

        return [
            ['title', 'title', 'description', 'http://url.com', $category],
            ['title2', 'title2', 'description2', 'http://url2.com', $category],
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}

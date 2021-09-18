<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Knp\Component\Pager\PaginatorInterface;
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

    public function testMustAddAVideoToRepository()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $categoryRepository->add($category);
        
        $video = Video::build(
            'title', 
            'description', 
            'http://url.com', 
            $category
        );
        $videoRepository = $this->entityManager->getRepository(Video::class);
        
        //Act
        $videoRepository->add($video);
        $id = $video->getId();
        $videoRecord = $videoRepository->find($id);

        //Assert
        $this->assertEquals('title', $videoRecord->getTitle());
        $this->assertEquals('description', $videoRecord->getDescription());
        $this->assertEquals('http://url.com', $videoRecord->getUrl());
        $this->assertEquals($category, $videoRecord->getCategory());
    }
    
    public function testMustlistAllVideos()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $video1 = Video::build('title', 'description', 'http://url.com', $category);
        $video2 = Video::build('title2', 'description2', 'http://url2.com', $category);
    
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
        $category = Category::build('title', 'color');
        $video = Video::build('title', 'description', 'http://url.com', $category);
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
        $video1 = Video::build('title', 'description', 'http://url.com', $category);
        $video2 = Video::build('title2', 'description2', 'http://url2.com', $category);
    
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

    /**
     * @dataProvider indexesToVerifyArrayOfNonAuthUsersVideos
     */
    public function testMustFetch5VideosForNonAuthUsers(int $index)
    {
        //Arrange
        $category = Category::build('title', 'color');
        $video1 = Video::build('title0', 'description0', 'http://url0.com', $category);
        $video2 = Video::build('title1', 'description1', 'http://url1.com', $category);
        $video3 = Video::build('title2', 'description2', 'http://url2.com', $category);
        $video4 = Video::build('title3', 'description3', 'http://url3.com', $category);
        $video5 = Video::build('title4', 'description4', 'http://url4.com', $category);
        $video6 = Video::build('title5', 'description5', 'http://url5.com', $category);

        $categoryRepository = $this->entityManager->getRepository(Category::class)
            ->add($category);
        $videoRepository = $this->entityManager->getRepository(Video::class)
            ->add($video1)
            ->add($video2)
            ->add($video3)
            ->add($video4)
            ->add($video5)
            ->add($video6);

        //Act
        $videos = $videoRepository->videosForNonAuthUsers(); 

        //Assert
        $this->assertCount(5, $videos);
        $this->assertEquals('title' . $index, $videos[$index]->getTitle());
        $this->assertEquals('description' . $index, $videos[$index]->getDescription());
        $this->assertEquals('http://url' . $index . '.com', $videos[$index]->getUrl());
        $this->assertEquals($category, $videos[$index]->getCategory());

    }

    public function testMustPaginateVideos()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $video1 = Video::build('title0', 'description0', 'http://url0.com', $category);
        $video2 = Video::build('title1', 'description1', 'http://url1.com', $category);

        $categoryRepository = $this->entityManager->getRepository(Category::class)
            ->add($category);
        $videoRepository = $this->entityManager->getRepository(Video::class)
            ->add($video1)
            ->add($video2);

        //Act
        $container = static::getContainer();
        $paginator = $container->get(PaginatorInterface::class);
        $paginationData = $videoRepository->paginate($paginator, 1);

        //Assert
        $this->assertCount(2, $paginationData['Resources']);
        $this->assertArrayNotHasKey('Previous page', $paginationData['Page']);
        $this->assertArrayNotHasKey('Next page', $paginationData['Page']);
        $this->assertEquals('/videos?page=1', $paginationData['Page']['Current page']);
    }

    public function dataForQueringByQueryParameter()
    {
        $category = Category::build('title', 'color');

        return [
            ['title', 'title', 'description', 'http://url.com', $category],
            ['title2', 'title2', 'description2', 'http://url2.com', $category],
        ];
    }
    
    public function indexesToVerifyArrayOfNonAuthUsersVideos()
    {
        return [
            [0], [1], [2], [3], [4] 
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}

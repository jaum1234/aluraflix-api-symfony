<?php

namespace App\Tests;

use App\Entity\Video;
use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use ApiPlatform\Core\Bridge\Symfony\Validator\Validator;

class CategoryTest extends KernelTestCase
{
    public function testMustCreateACategory(): void
    {
        //Arrange & Act
        $category = Category::build('title', 'color');

        //Assert
        $this->assertEquals('title', $category->getTitle());
        $this->assertEquals('color', $category->getColor());
    }

    public function testMustUpdateACategory()
    {
        //Arrange
        $category = Category::build('title', 'color');

        //Act
        $category->setTitle('New title');
        $category->setColor('red');

        //Assert
        $this->assertEquals('New title', $category->getTitle());
        $this->assertEquals('red', $category->getColor());
    }

    public function testMustRelateVideosToACategory()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $video1 = Video::build('video 1', 'description 1', 'http://url1.com', $category);
        $video2 = Video::build('video 2', 'description 2', 'http://url2.com', $category);
        
        //Act
        $category->addVideo($video1);
        $category->addVideo($video2);
        $videos = $category->getVideos();

        //Assert
        $this->assertCount(2, $videos);
        
        $this->assertEquals('video 1', $videos[0]->getTitle());
        $this->assertEquals('description 1', $videos[0]->getDescription());
        $this->assertEquals('http://url1.com', $videos[0]->getUrl());

        $this->assertEquals('video 2', $videos[1]->getTitle());
        $this->assertEquals('description 2', $videos[1]->getDescription());
        $this->assertEquals('http://url2.com', $videos[1]->getUrl());
    }

    public function testMustSetDefautCategoryToRelatedEntities()
    {
        //Arrange
        $defaultCategory =  Category::build('default title', 'default color');
        $category = Category::build('title', 'color');
        $video = Video::build('video', 'description', 'http://url.com', $category);
        $category->addVideo($video);

        //Act
        $category->setDefaultValuesForRelatedEntities($defaultCategory);
        $videos = $category->getVideos();
        $videoCategory = $videos[0]->getCategory();

        //Assert
        $this->assertEquals($defaultCategory, $videoCategory);
    }

}

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
        $category = new Category('title', 'color');

        //Assert
        $this->assertEquals('title', $category->getTitle());
        $this->assertEquals('color', $category->getColor());
    }

    public function testMustUpdateACategory()
    {
        //Arrange
        $category = new Category('title', 'color');

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
        $category = new Category('title', 'color');
        $video1 = new Video('video 1', 'description 1', 'http://url1.com', $category);
        $video2 = new Video('video 2', 'description 2', 'http://url2.com', $category);
        
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

    /**
     * @dataProvider dataForValidator
     */
    public function testCategoryMustBeValidated(int $expectedErros, string $title, string $color)
    {
        //Arrange
        $category = new Category($title, $color);

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        //Act
        $errors = $validator->validate($category);

        //Assert
        $this->assertEquals($expectedErros, count($errors));
    }

    public function testMustSetDefautCategoryToRelatedEntities()
    {
        //Arrange
        $defaultCategory =  new Category('default title', 'default color');
        $category = new Category('title', 'color');
        $video = new Video('video', 'description', 'http://url.com', $category);
        $category->addVideo($video);

        //Act
        $category->setDefaultValuesForRelatedEntities($defaultCategory);
        $videos = $category->getVideos();
        $videoCategory = $videos[0]->getCategory();

        //Assert
        $this->assertEquals($defaultCategory, $videoCategory);
    }

    public function dataForValidator()
    {
        return [
            "Succeeds when data is correct" => [0, 'title', 'color'],
            "Fails when 'color' is missing" => [1, 'title', ''],
            "Fails when 'title' is missing" => [1, '', 'color'],
            "Fails when 'title' and 'color' is missing" => [2, '', '']
        ];
    }

}

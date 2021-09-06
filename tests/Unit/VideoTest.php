<?php

namespace App\Tests;

use App\Entity\Video;
use App\Entity\Category;
use Symfony\Component\Validator\Validation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VideoTest extends KernelTestCase
{
    public function testMustCreateAVideo()
    {
        //Arrange and Act
        $category = new Category('title', 'color');
        $video = new Video('title', 'description', 'http://url.com', $category);

        //Assert
        $this->assertEquals('title', $video->getTitle());
        $this->assertEquals('description', $video->getDescription());
        $this->assertEquals('http://url.com', $video->getUrl());
        $this->assertEquals($category, $video->getCategory());
    }

    public function testMustUpdateAVideo()
    {
        //Arrange
        $category = new Category('title', 'color');
        $category2 = new Category('title2', 'color2');
        $video = new Video('title', 'description', 'http://url.com', $category);

        //Act
        $video->setTitle('title updated');
        $video->setDescription('description updated');
        $video->setUrl('http://updatedurl.com');
        $video->setCategory($category2);

        //Assert
        $this->assertEquals('title updated', $video->getTitle());
        $this->assertEquals('description updated', $video->getDescription());
        $this->assertEquals('http://updatedurl.com', $video->getUrl());
        $this->assertEquals($category2, $video->getCategory());
    }

    /**
     * @dataProvider dataForValidator
     */
    public function testMustValidateAVideo(
        int $expectedErros, 
        string $title, 
        string $description, 
        string $url,
        Category $category
        )
    {
         //Arrange
         $video = new Video($title, $description, $url, $category);

         $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        //Act
        $errors = $validator->validate($video);

        //Assert
        $this->assertEquals($expectedErros, count($errors));
    }

    public function dataForValidator()
    {
        $category = new Category('title', 'color');

        return [
            "Succeeds when data is correct" => [0, 'title', 'description', 'http://url.com', $category],
            "Fails when 'title' is missing" => [1, '', 'description', 'http://url.com', $category],
            "Fails when 'description' is missing" => [1, 'title', '', 'http://url.com', $category],
            "Fails when 'url' is missing" => [1, 'title', 'description', '', $category],
            "Fails when 'url' is invalid" => [1, 'title', 'description', 'url', $category]
        ];
    }
}

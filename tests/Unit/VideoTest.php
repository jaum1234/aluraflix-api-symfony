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
        $category = Category::build('title', 'color');
        $video = Video::build('title', 'description', 'http://url.com', $category);

        //Assert
        $this->assertEquals('title', $video->getTitle());
        $this->assertEquals('description', $video->getDescription());
        $this->assertEquals('http://url.com', $video->getUrl());
        $this->assertEquals($category, $video->getCategory());
    }

    public function testMustUpdateAVideo()
    {
        //Arrange
        $category = Category::build('title', 'color');
        $category2 = Category::build('title2', 'color2');
        $video = Video::build('title', 'description', 'http://url.com', $category);

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
    ) {
        
        //Arrange
        $video = Video::build($title, $description, $url, $category);

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
        $category = Category::build('title', 'color');

        return [
            "Succeeds when data is correct" => [0, 'title', 'description', 'http://url.com', $category],
            "Fails when 'title' is missing" => [1, '', 'description', 'http://url.com', $category],
            "Fails when 'description' is missing" => [1, 'title', '', 'http://url.com', $category],
            "Fails when 'url' is missing" => [1, 'title', 'description', '', $category],
            "Fails when 'url' is invalid" => [1, 'title', 'description', 'url', $category]
        ];
    }
}

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



}

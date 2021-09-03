<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Video;
use Doctrine\ORM\EntityManager;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{

    public function testMustCreateACategory(): void
    {
        $category = new Category('title', 'color');

        $this->assertEquals('title', $category->getTitle());
        $this->assertEquals('color', $category->getColor());
    }

    public function testMustUpdateACategory()
    {
        $category = new Category('title', 'color');

        $category->setTitle('New title');
        $category->setColor('red');

        $this->assertEquals('New title', $category->getTitle());
        $this->assertEquals('red', $category->getColor());
    }

    public function testMustGetAllVideosOfACategory()
    {
        $category = new Category('title', 'color');
        $video1 = new Video('video 1', 'description 1', 'http://url1.com', $category);
        $video2 = new Video('video 2', 'description 2', 'http://url2.com', $category);
        $video3 = new Video('video 3', 'description 3', 'http://url3.com', $category);

        $category->addVideo($video1);
    }

}

<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Video;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $video = new Video();
        $video->setTitle("Título para teste");
        $video->setDescription("Descricao para teste");
        $video->setUrl("Url para teste");
        $video->setCategory(Category::build('title', 'color'));

        $manager->persist($video);
        $manager->flush();
    }
}

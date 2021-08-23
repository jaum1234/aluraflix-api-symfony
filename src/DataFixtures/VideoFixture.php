<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VideoFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $video = new Video();
        $video->setTitle("Título para teste");
        $video->setDescription("Descricao para teste");
        $video->setUrl("Url para teste");

        $manager->persist($video);
        $manager->flush();
    }
}

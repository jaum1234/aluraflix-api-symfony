<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Video::class);
        $videos = $repository->findAll();

        return $this->json([
            'Videos were listed with success!',
            $videos
        ]);
    }

    public function show(VideoRepository $videoRepository,int $id): Response
    {
        $video = $videoRepository->findOneByIdJoinedToCategory($id);
        

        return $this->json([
            'Video was found with success!',
            $video
        ]);
    }

    public function delete(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Video::class);
        $video = $repository->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($video);
        $entityManager->flush();

        return $this->json([
                $video->getTitle() . ' was deleted with success!'
            ], 
            410
        );
    }
}



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
    protected string $class;
    
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository($this->class);
        $resources = $repository->findAll();

        return $this->json([
            'Videos were listed with success!',
            $resources
        ]);
    }

    public function show(VideoRepository $resourceRepository, int $id): Response
    {
        $resource = $resourceRepository->find($id);
        
        return $this->json([
            'Video was found with success!',
            $resource
        ]);
    }

    public function delete(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository($this->class);
        $resource = $repository->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($resource);
        $entityManager->flush();

        return $this->json([
                $resource->getTitle() . ' was deleted with success!'
            ], 
            410
        );
    }
}



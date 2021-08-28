<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\IOneToManyEntity;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping\OneToMany;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    protected string $class;
    
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository($this->class);
        $resources = $repository->findAll();

        return $this->json([
            'Listed',
            $resources
        ]);
    }

    public function show(VideoRepository $resourceRepository, int $id): Response
    {
        $resource = $resourceRepository->find($id);
        
        return $this->json([
            'Found',
            $resource
        ]);
    }

    public function store(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $resource = $this->saveEntity($request);

        $entityManager->persist($resource);
        $entityManager->flush();

        return $this->json([
            'Created',
            $resource
        ], 201);
    }

    public function put(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $resource = $this->updateEntity($request, $id);

        $entityManager->flush();

        return $this->json([
            'Updated',
            $resource
        ], 200);
    }

    public function delete(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository($this->class);
        $resource = $repository->find($id);

        if ($resource instanceof IOneToManyEntity) {
            $resourceWithIdOne = $repository->find(1);
            $resource->setDefaultValuesForRelatedEntities($resourceWithIdOne);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($resource);
        $entityManager->flush();

        return $this->json([
                $resource->getTitle() . ' deleted!'
            ], 
            410
        );
    }

    abstract protected function saveEntity(Request $request);
    abstract protected function updateEntity(Request $request, int $id);
}



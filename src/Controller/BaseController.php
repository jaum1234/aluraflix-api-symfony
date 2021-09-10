<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\IRelatedEntitiesCantBeDeleted;
use App\Entity\Video;
use App\Repository\VideoRepository;
use App\Service\ResourcesValidator;
use Doctrine\ORM\EntityManagerInterface;
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
    protected $repository;
    
    public function index(Request $request): Response
    {
        try {
            if ($request->query->has('q')) {
                $queryParameter = $request->query->get('q');
                $resources = $this->repository->findByQueryParameter($queryParameter);
                return $this->json([
                    'Found',
                    $resources
                ]);
            }
        } catch (\Doctrine\ORM\ORMException $e) {
            return $this->json(['ERROR' => 'This entity does not support searches by query parameter.']);
        }


        $resources = $this->repository->findAll();

        return $this->json([
            'Listed',
            $resources
        ]);
    }
    

    public function show(int $id): Response
    {
        $repository = $this->getDoctrine()
            ->getRepository($this->class);
        $resource = $repository->find($id);
        
        return $this->json([
            'Found',
            $resource
        ]);
    }

    public function store(Request $request, ResourcesValidator $validator, EntityManagerInterface $entityManager)
    {    
        $resource = $this->saveEntity($request);

        $validation = $validator->validate($resource);

        if (!$validation['success']) {
            return $this->json([
                'errors' => $validation['errors'][0]
            ]);
        }

        $this->repository->add($resource);

        return $this->json([
            'Created',
            $resource
        ], 201);
    }

    public function put(
        Request $request, 
        ResourcesValidator $validator, 
        int $id, 
        EntityManagerInterface $entityManager
        ): Response
    {
        $resource = $this->updateEntity($request, $id);

        $validation = $validator->validate($resource);

        if (!$validation['success']) {
            return $this->json([
                'errors' => $validation['errors'][0]
            ]);
        }

        $entityManager->flush();

        return $this->json([
            'Updated',
            $resource
        ], 200);
    }

    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $repository = $this->getDoctrine()->getRepository($this->class);
        $resource = $repository->find($id);

        if ($resource instanceof IRelatedEntitiesCantBeDeleted) {
            $resourceWithIdOne = $repository->find(1);
            $resource->setDefaultValuesForRelatedEntities($resourceWithIdOne);
        }

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



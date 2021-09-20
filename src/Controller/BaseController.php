<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Category;
use App\Repository\VideoRepository;
use App\Service\ResourcesValidator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\IRelatedEntitiesCantBeDeleted;
use DomainException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPUnit\Framework\isNull;

abstract class BaseController extends AbstractController
{
    protected string $class;
    protected $repository;

    public function index(PaginatorInterface $paginatorInterface, Request $request): Response
    {
        try {
            if ($request->query->has('q')) {
                $queryParameter = $request->query->get('q');
                $resources = $this->repository->findByQueryParameter($queryParameter);

                $currentPage = $request->query->getInt('page', 1);
                $paginationData = $this->repository->paginate($paginatorInterface, $currentPage);

                if (empty($resources)) {
                    throw new \Exception('No videos were found');
                }

                return $this->json([
                    ['status' => 'Listed'],
                    ['page info' => $paginationData['Page']],
                    ['resources' => $paginationData['Resources']]
                ]);
            }
        } catch (\Doctrine\ORM\ORMException $e) {
            return $this->json([
                ['message' => 'This entity does not support searches by query parameter.']
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'Not found',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    
        $currentPage = $request->query->getInt('page', 1);
        $paginationData = $this->repository->paginate($paginatorInterface, $currentPage);

        return $this->json([
            ['status' => 'Listed'],
            ['page info' => $paginationData['Page']],
            ['resources' => $paginationData['Resources']],
        ]);
    }
    

    public function show(int $id): Response
    {
        try {
            $resource = $this->repository->find($id);

            if (is_null($resource)) {
                throw new \DomainException('Resource does not exist.');
            }

        } catch (\DomainException $e) {
            return $this->json([
                'status' => 'Not found', 
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
        
        return $this->json([
            ['status' => 'Found'],
            ['resource' => $resource]
        ], Response::HTTP_OK);
    }

    public function store(Request $request, ResourcesValidator $validator, EntityManagerInterface $entityManager)
    {    
        $resource = $this->saveEntity($request);

        $validation = $validator->validate($resource);

        if (!$validation['success']) {
            return $this->json([
                'errors' => $validation['errors'][0]
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->repository->add($resource);

        return $this->json([
            ['status' => 'Created'],
            ['resource' => $resource]
        ], Response::HTTP_CREATED);
    }

    public function put(
        EntityManagerInterface $entityManager,
        ResourcesValidator $validator, 
        Request $request, 
        int $id
        ): Response
    {
        try {
            $resource = $this->updateEntity($request, $id);
        } catch (\DomainException $e) {
            return $this->json([
                ['status' => 'Not updatable'],
                ['messagem' => $e->getMessage()]
            ], Response::HTTP_BAD_REQUEST);
        }

        $validation = $validator->validate($resource);

        if (!$validation['success']) {
            return $this->json([
                'errors' => $validation['errors'][0]
            ]);
        }

        $entityManager->flush();

        return $this->json([
            ['status' => 'Updated'],
            ['resource' => $resource]
        ], Response::HTTP_OK);
    }

    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        try {
            $resource = $this->deleteEntity($id);
        } catch (\DomainException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->remove($resource);
        $entityManager->flush();

        return $this->json([
                ['status' => 'Deleted'],
                ['message' => $resource->getTitle() . ' deleted!']
            ], 
            Response::HTTP_GONE
        );
    }

    abstract protected function deleteEntity(int $id);
    abstract protected function saveEntity(Request $request);
    abstract protected function updateEntity(Request $request, int $id);
}



<?php

namespace App\Controller;

use App\Entity\Category;
use App\Controller\BaseController;
use App\Controller\OneToManyEntity;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends BaseController
{
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
        $this->class = Category::class;
    }
    
    protected function saveEntity(Request $request): Category
    {
        $data = $request->toArray();
        $category = Category::build($data['title'], $data['color']);
        
        return $category;
    }

    
    protected function updateEntity(Request $request, int $id): Category
    {
        if ($id == 1) {
            throw new \DomainException("This category cannot be updated.");
        }
    
        $category = $this->repository->find($id);
        
        $data = $request->toArray();

        $category->setTitle($data['title']);
        $category->setColor($data['color']);

        return $category;
    }

    protected function deleteEntity(int $id)
    {
        $resource = $this->repository->find($id);

        if (is_null($resource)) {
            throw new \DomainException("This category does not exist.");
        }
        
        if ($id == 1) {
            throw new \DomainException("This category cannot be deleted.");
        }

        $resourceWithIdOne = $this->repository->find(1);
        $resource->setDefaultValueForRelatedEntities($resourceWithIdOne);

        return $resource;
    }

    public function searchVideosPerCategory(int $id)
    {
      
        try {
            $category = $this->repository->find($id);
            if (is_null($category)) throw new Exception();
        } catch (\Exception $e) {
            return $this->json('No category with id ' . $id . ' found.');
        }   
        
        $videos = $category->getVideos();

        return $this->json([
            'Found',
            $videos
            ], 200);
    }

}

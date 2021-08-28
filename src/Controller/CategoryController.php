<?php

namespace App\Controller;

use App\Entity\Category;
use App\Controller\BaseController;
use App\Controller\OneToManyEntity;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->class = Category::class;
    }
    
    protected function saveEntity(Request $request): Category
    {
        $data = $request->toArray();
        
        $category = new Category(
            $data['title'], 
            $data['color']
        );
        
        return $category;
    }

    
    protected function updateEntity(Request $request, int $id): Category
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($id);
        
        $data = $request->toArray();

        $category->setTitle($data['title']);
        $category->setColor($data['color']);

        return $category;
    }

    public function searchVideosPerCategory(int $id)
    {
        $repository = $this->getDoctrine()
            ->getRepository(Category::class);

        try {
            $category = $repository->find($id);
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

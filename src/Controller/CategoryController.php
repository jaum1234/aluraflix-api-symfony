<?php

namespace App\Controller;

use App\Entity\Category;
use App\Controller\BaseController;
use App\Controller\OneToManyEntity;
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

    protected function removeEntity()
    {
        return true;
    }
}

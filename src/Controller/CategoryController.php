<?php

namespace App\Controller;

use App\Entity\Category;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends BaseController
{

    public function __construct()
    {
        $this->class = Category::class;
    }
    
    public function store(Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $data = $request->toArray();
        $category = new Category(
            $data['title'], 
            $data['color']
        );

        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            $errorString = (string) $errors;

            return $this->json($errorString);
        }
        
        $entityManager->persist($category);
        $entityManager->flush();
        
        return $this->json([
            'category was created with success!',
            $category
        ], 201);
    }

    
    public function put(Request $request, ValidatorInterface $validator, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Category::class);
        
        $data = $request->toArray();
        
        $category = $repository->find($id);
        $category->updatePropertiesValues($data);

        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            $errorString = (string) $errors;

            return $this->json($errorString);
        }

        $entityManager->flush();

        return $this->json([
                'category was updated with success!',
                $category
            ], 
        );
    }
}

<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Category;
use App\Controller\BaseController;
use App\Repository\VideoRepository;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class VideoController extends BaseController
{

    public function __construct()
    {
        $this->class = Video::class;
    }
   
    public function store(Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $data = $request->toArray();
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($data['category_id']);
        
        $video = new Video(
            $data['title'], 
            $data['description'], 
            $data['url'],
            $category
        );

        $errors = $validator->validate($video);

        if (count($errors) > 0) {
            $errorString = (string) $errors;

            return $this->json($errorString);
        }
        
        $entityManager->persist($video);
        $entityManager->flush();
        
        return $this->json([
            'Video was created with success!',
            $data
        ], 201);
    }

    public function put(Request $request, ValidatorInterface $validator, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $videoRepository = $this->getDoctrine()->getRepository(Video::class);
        $cateogoryRepository = $this->getDoctrine()->getRepository(Category::class);
 
        $data = $request->toArray();
        $category = $cateogoryRepository->find($data['category_id']);
        
        $video = $videoRepository->find($id);
        $video->updatePropertiesValues($data, $category);

        $errors = $validator->validate($video);

        if (count($errors) > 0) {
            $errorString = (string) $errors;

            return $this->json($errorString);
        }

        $entityManager->flush();

        return $this->json([
                'Video was updated with success!',
                $data
            ], 
        );
    }

}

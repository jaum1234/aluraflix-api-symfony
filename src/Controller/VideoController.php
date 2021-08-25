<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class VideoController extends AbstractController
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

    /**
     * @Route("/{id}", name="video", methods={"GET"})
     */
    public function show(VideoRepository $videoRepository,int $id): Response
    {
        $video = $videoRepository->findOneByIdJoinedToCategory($id);
        

        return $this->json([
            'Video was found with success!',
            $video
        ]);
    }

    /**
     * @Route("", name="create_video", methods={"POST"})
     */
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

    /**
     * @Route("/{id}", name="update_video", methods={"PUT"})
     */
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

    /**
     * @Route("/{id}", name="delete_video", methods={"DELETE"})
     */
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
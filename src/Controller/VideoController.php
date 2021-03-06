<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Category;
use App\Controller\BaseController;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class VideoController extends BaseController
{
    protected $repository;

    public function __construct(VideoRepository $repository)
    {
        $this->class = Video::class;
        $this->repository = $repository;
    }

    public function free()
    {
        $videos = $this->repository->videosForNonAuthUsers();

        return $this->json($videos);
    }
   
    protected function saveEntity(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        
        $data = $request->toArray();

        $category = $this->findCategoryByCategoryId($data, $repository);
    
        $video = Video::build(
            $data['title'], 
            $data['description'], 
            $data['url'],
            $category
        );

        return $video;
    }

    private function findCategoryByCategoryId($request, $repository)
    {
        if (!array_key_exists('category_id', $request)) {
            $category = $repository->find(1);
        } else {
            $category = $repository->find($request['category_id']);
        }

        return $category;
    }

    protected function updateEntity(Request $request, int $id)
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
 
        $data = $request->toArray();
        $category = $categoryRepository->find($data['category_id']);
        
        $video = $this->repository->find($id);

        $video->setTitle("New Title");
        $video->setDescription("New Description");
        $video->setUrl("http://exemple.com");
        $video->setCategory($category);

        return $video;
    }

    protected function deleteEntity(int $id) {
        $resource = $this->repository->find($id);

        if (is_null($resource)) {
            throw new \DomainException("This video does not exist.");
        }

        return $resource;
    }

}

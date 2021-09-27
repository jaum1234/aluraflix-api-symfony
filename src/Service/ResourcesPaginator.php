<?php

namespace App\Service;

use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\PaginatorInterface;

class ResourcesPaginator
{


    public function paginate(PaginatorInterface $paginatorInterface, ObjectRepository $repository, int $page)
    {
        
        $videosAll = $repository->findAll();
        $itemsPerPage = 5;
        $lastPage = ceil(count($videosAll)/$itemsPerPage);

        $videosPagination = $paginatorInterface->paginate(
            $videosAll,
            $page,
            $itemsPerPage
        );

        $pageNumbers = [
            'Previous' => $page - 1,
            'Current' => $page,
            'Next' => $page + 1
        ];

        $pages = [
            'Previous page' => '/videos?page=' . ($pageNumbers['Previous']),
            'Current page' => '/videos?page=' . $pageNumbers['Current'],
            'Next page' => '/videos?page=' . ($pageNumbers['Next'])
        ];

        if ($pageNumbers['Previous'] < 1) {
            unset($pages['Previous page']);
        }

        if ($pageNumbers['Current'] == $lastPage) {
            unset($pages['Next page']);
        }

        return [
            'Resources' => $videosPagination, 
            'Page' => $pages
        ];
    }
}
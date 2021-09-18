<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function add($category)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return $this;
    }

    public function paginate(PaginatorInterface $paginator, int $page)
    {
        $categoriesAll = $this->findAll();
        $itemsPerPage = 5;
        $lastPage = ceil(count($categoriesAll)/$itemsPerPage);

        $categoriesPagination = $paginator->paginate(
            $categoriesAll,
            $page,
            $itemsPerPage
        );

        $pageNumbers = [
            'Previous' => $page - 1,
            'Current' => $page,
            'Next' => $page + 1
        ];

        $pages = [
            'Previous page' => '/categories?page=' . ($pageNumbers['Previous']),
            'Current page' => '/categories?page=' . $pageNumbers['Current'],
            'Next page' => '/categories?page=' . ($pageNumbers['Next'])
        ];

        if ($pageNumbers['Previous'] < 1) {
            unset($pages['Previous page']);
        }

        if ($pageNumbers['Current'] == $lastPage) {
            unset($pages['Next page']);
        }

        return [
            'Resources' => $categoriesPagination, 
            'Page' => $pages
        ];
    }
}

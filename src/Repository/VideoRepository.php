<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Container0WAxwPn\getContainer_GetenvService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function add($video): self
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($video);
        $entityManager->flush();

        return $this;
    }
    
    public function findByQueryParameter($queryParameter)
    {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->where(
                $queryBuilder
                    ->expr()
                    ->like('r.title', ':queryParameter')
                )
            ->setParameter('queryParameter', '%' . $queryParameter . '%');

        $query = $queryBuilder->getQuery();
        
        return $query->execute();
    }

    public function videosForNonAuthUsers()
    {
        $queryBuilder = $this->createQueryBuilder('v');
        $queryBuilder
            ->setMaxResults(5)
            ->setFirstResult(0);
        
        $query = $queryBuilder->getQuery();

        return $query->execute();
    }

    public function paginate(PaginatorInterface $paginator, int $page)
    {
        $videosAll = $this->findAll();
        $itemsPerPage = 5;
        $lastPage = ceil(count($videosAll)/$itemsPerPage);

        $videosPagination = $paginator->paginate(
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

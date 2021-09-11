<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function add($data)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($data);
        $entityManager->flush();
    }
}

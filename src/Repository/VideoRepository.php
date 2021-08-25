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

    public function findOneByIdJoinedToCategory(int $videoId): ?Video
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT v, c
            FROM App\Entity\Video v
            INNER JOIN v.category c
            WHERE v.id = :id'
        )->setParameter('id', $videoId);

        return $query->getOneOrNullResult();
    }
}

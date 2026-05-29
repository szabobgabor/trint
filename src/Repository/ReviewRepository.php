<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review[]
     */
    public function listReviews(?int $cursor = null): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->join('r.company', 'c')
            ->addSelect('c')
            ->orderBy('r.id', 'DESC');

        if ($cursor !== null) {
            $queryBuilder->andWhere('r.id < :cursor')
                ->setParameter('cursor', $cursor, ParameterType::INTEGER);
        }

        return $queryBuilder
            ->getQuery()
            ->setMaxResults(10)
            ->getResult();
    }
}

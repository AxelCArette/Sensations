<?php

namespace App\Repository;

use App\Entity\RedactionArticles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RedactionArticles>
 *
 * @method RedactionArticles|null find($id, $lockMode = null, $lockVersion = null)
 * @method RedactionArticles|null findOneBy(array $criteria, array $orderBy = null)
 * @method RedactionArticles[]    findAll()
 * @method RedactionArticles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedactionArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedactionArticles::class);
    }

    //    /**
    //     * @return RedactionArticles[] Returns an array of RedactionArticles objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RedactionArticles
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

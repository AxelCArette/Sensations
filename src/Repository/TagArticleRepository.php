<?php

namespace App\Repository;

use App\Entity\TagArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TagArticle>
 *
 * @method TagArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagArticle[]    findAll()
 * @method TagArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagArticle::class);
    }

    //    /**
    //     * @return TagArticle[] Returns an array of TagArticle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TagArticle
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\FormationsGratuite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormationsGratuite>
 *
 * @method FormationsGratuite|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormationsGratuite|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormationsGratuite[]    findAll()
 * @method FormationsGratuite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationsGratuiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormationsGratuite::class);
    }

    //    /**
    //     * @return FormationsGratuite[] Returns an array of FormationsGratuite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FormationsGratuite
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

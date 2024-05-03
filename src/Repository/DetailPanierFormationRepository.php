<?php

namespace App\Repository;

use App\Entity\DetailPanierFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailPanierFormation>
 *
 * @method DetailPanierFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailPanierFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailPanierFormation[]    findAll()
 * @method DetailPanierFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailPanierFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailPanierFormation::class);
    }

//    /**
//     * @return DetailPanierFormation[] Returns an array of DetailPanierFormation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DetailPanierFormation
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

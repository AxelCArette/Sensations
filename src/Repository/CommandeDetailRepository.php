<?php

namespace App\Repository;

use App\Entity\CommandeDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeDetail>
 *
 * @method CommandeDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeDetail[]    findAll()
 * @method CommandeDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeDetail::class);
    }

    /**
     * Récupère les détails des commandes liées à un utilisateur spécifique
     *
     * @param User $user
     * @return CommandeDetail[]
     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('cd')
            ->join('cd.commande', 'c')
            ->where('c.Utilisateur = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}

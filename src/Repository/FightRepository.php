<?php

namespace App\Repository;

use App\Entity\Fight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Fight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fight[]    findAll()
 * @method Fight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FightRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Fight::class);
    }

    public function findExistingFights($id) {

        return $this->createQueryBuilder('f')
                    ->select('f')
                    ->where('f.fighter = ' . $id)
                    ->orWhere('f.opponent = ' . $id)
                    ->orderBy('f.createdAt', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    // /**
    //  * @return Fight[] Returns an array of Fight objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fight
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

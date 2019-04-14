<?php

namespace App\Repository;

use App\Entity\AdminPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdminPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminPost[]    findAll()
 * @method AdminPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminPost::class);
    }

    // /**
    //  * @return AdminPost[] Returns an array of AdminPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminPost
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

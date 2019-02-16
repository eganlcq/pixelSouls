<?php

namespace App\Repository;

use App\Entity\TypePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePost[]    findAll()
 * @method TypePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypePost::class);
    }

    // /**
    //  * @return TypePost[] Returns an array of TypePost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypePost
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

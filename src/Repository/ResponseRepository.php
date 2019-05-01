<?php

namespace App\Repository;

use App\Entity\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Response|null find($id, $lockMode = null, $lockVersion = null)
 * @method Response|null findOneBy(array $criteria, array $orderBy = null)
 * @method Response[]    findAll()
 * @method Response[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Response::class);
    }

    public function findOrderedMessages($id) {

        return $this->createQueryBuilder('m')
                    ->select('m')
                    ->join('m.relatedPost', 'p')
                    ->where('p.id = ' . $id)
                    ->orderBy('m.createdAt', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByContent($content) {

        return $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.content LIKE :content')
                    ->setParameter('content', '%' . $content . '%')
                    ->getQuery()
                    ->getResult();
    }
}

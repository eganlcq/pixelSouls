<?php

namespace App\Repository;

use App\Entity\Fighter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Fighter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fighter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fighter[]    findAll()
 * @method Fighter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FighterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Fighter::class);
    }

    public function findAllOpponents($id) {

        return $this->createQueryBuilder('f')
                    ->select('f')
                    ->join('f.owner', 'u')
                    ->where('u.id != ' . $id)
                    ->andWhere('f.isActive = true')
                    ->andWhere()
                    ->getQuery()
                    ->getResult();
    }

    public function searchByName($name) {

        return $this->createQueryBuilder('f')
                    ->select('f')
                    ->where('f.name LIKE :name')
                    ->andWhere('f.isActive = true')
                    ->setParameter('name', '%' . $name . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByUser($pseudo) {

        return $this->createQueryBuilder('f')
                    ->select('f')
                    ->join('f.owner', 'u')
                    ->where('u.pseudo LIKE :pseudo')
                    ->andWhere('f.isActive = true')
                    ->setParameter('pseudo', '%' . $pseudo . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByNameAdmin($name) {

        return $this->createQueryBuilder('f')
                    ->select('f')
                    ->where('f.name LIKE :name')
                    ->setParameter('name', '%' . $name . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByUserAdmin($pseudo) {

        return $this->createQueryBuilder('f')
                    ->select('f')
                    ->join('f.owner', 'u')
                    ->where('u.pseudo LIKE :pseudo')
                    ->setParameter('pseudo', '%' . $pseudo . '%')
                    ->getQuery()
                    ->getResult();
    }
}

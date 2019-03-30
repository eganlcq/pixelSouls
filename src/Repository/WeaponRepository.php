<?php

namespace App\Repository;

use App\Entity\Weapon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Weapon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weapon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weapon[]    findAll()
 * @method Weapon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeaponRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Weapon::class);
    }

    public function findRemainingWeapons($id) {

        $qb = $this->createQueryBuilder('w');
        return $qb->select('w')
                    ->where($qb->expr()->notIn('w.id', $this->findOwnedWeapons($id)))
                    ->getQuery()
                    ->getResult();
    }

    public function findOwnedWeapons($id) {

        return array_map('current', $this->createQueryBuilder('w')
                    ->select('w.id')
                    ->join('w.owners', 'f')
                    ->where('f.id = ' . $id)
                    ->getQuery()
                    ->getResult());
    }
}

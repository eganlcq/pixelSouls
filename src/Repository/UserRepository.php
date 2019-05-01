<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function searchByPseudo($pseudo) {

        return $this->createQueryBuilder('u')
                    ->select('u')
                    ->where('u.pseudo LIKE :pseudo')
                    ->setParameter('pseudo', '%' . $pseudo . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByFirstName($firstName) {

        return $this->createQueryBuilder('u')
                    ->select('u')
                    ->where('u.firstName LIKE :firstName')
                    ->setParameter('firstName', '%' . $firstName . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByLastName($lastName) {

        return $this->createQueryBuilder('u')
                    ->select('u')
                    ->where('u.lastName LIKE :lastName')
                    ->setParameter('lastName', '%' . $lastName . '%')
                    ->getQuery()
                    ->getResult();
    }
}

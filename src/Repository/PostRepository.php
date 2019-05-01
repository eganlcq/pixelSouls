<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findOrderedPosts() {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->orderBy('p.createdAt', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByTitle($title) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.title LIKE :title')
                    ->andWhere('p.isActive = true')
                    ->orderBy('p.createdAt', 'DESC')
                    ->setParameter('title', '%' . $title . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByUser($pseudo) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->join('p.writer', 'u')
                    ->where('u.pseudo LIKE :pseudo')
                    ->andWhere('p.isActive = true')
                    ->orderBy('p.createdAt', 'DESC')
                    ->setParameter('pseudo', '%' . $pseudo . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByType($type) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->join('p.type', 't')
                    ->where('t.name = :type')
                    ->andWhere('p.isActive = true')
                    ->orderBy('p.createdAt', 'DESC')
                    ->setParameter('type', $type)
                    ->getQuery()
                    ->getResult();
    }

    public function searchByTitleAndType($title, $type) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->join('p.type', 't')
                    ->where('p.title LIKE :title')
                    ->andWhere('t.name = :type')
                    ->andWhere('p.isActive = true')
                    ->orderBy('p.createdAt', 'DESC')
                    ->setParameter('title', '%' . $title . '%')
                    ->setParameter('type', $type)
                    ->getQuery()
                    ->getResult();
    }

    public function searchByUserAndType($pseudo, $type) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->join('p.type', 't')
                    ->join('p.writer', 'u')
                    ->where('u.pseudo LIKE :pseudo')
                    ->andWhere('t.name = :type')
                    ->andWhere('p.isActive = true')
                    ->orderBy('p.createdAt', 'DESC')
                    ->setParameter('pseudo', '%' . $pseudo . '%')
                    ->setParameter('type', $type)
                    ->getQuery()
                    ->getResult();
    }

    public function searchByTitleAdmin($title) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.title LIKE :title')
                    ->setParameter('title', '%' . $title . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByUserAdmin($pseudo) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->join('p.writer', 'u')
                    ->where('u.pseudo LIKE :pseudo')
                    ->setParameter('pseudo', '%' . $pseudo . '%')
                    ->getQuery()
                    ->getResult();
    }

    public function searchByTypeAdmin($type) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->join('p.type', 't')
                    ->where('t.name = :type')
                    ->setParameter('type', $type)
                    ->getQuery()
                    ->getResult();
    }

    public function searchByTitleAndTypeAdmin($title, $type) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->join('p.type', 't')
                    ->where('p.title LIKE :title')
                    ->andWhere('t.name = :type')
                    ->setParameter('title', '%' . $title . '%')
                    ->setParameter('type', $type)
                    ->getQuery()
                    ->getResult();
    }

    public function searchByUserAndTypeAdmin($pseudo, $type) {

        return $this->createQueryBuilder('p')
                    ->select('p')
                    ->join('p.type', 't')
                    ->join('p.writer', 'u')
                    ->where('u.pseudo LIKE :pseudo')
                    ->andWhere('t.name = :type')
                    ->setParameter('pseudo', '%' . $pseudo . '%')
                    ->setParameter('type', $type)
                    ->getQuery()
                    ->getResult();
    }
}

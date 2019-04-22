<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Stats {

    private $manager;

    public function __construct(ObjectManager $manager) {

        $this->manager = $manager;
    }

    public function getStats() {

        $users = $this->getUsersCount();
        $characters = $this->getCharactersCount();
        $posts = $this->getPostsCount();
        $comments = $this->getCommentsCount();

        return compact('users', 'characters', 'posts', 'comments');
    }

    public function getUsersCount() {

        return $this->manager->createQuery(
            'SELECT COUNT(u) FROM App\Entity\User u'
        )->getSingleScalarResult();
    }

    public function getCharactersCount() {

        return $this->manager->createQuery(
            'SELECT COUNT(f) FROM App\Entity\Fighter f'
        )->getSingleScalarResult();
    }

    public function getPostsCount() {

        return $this->manager->createQuery(
            'SELECT COUNT(p) FROM App\Entity\Post p'
        )->getSingleScalarResult();
    }

    public function getCommentsCount() {

        return $this->manager->createQuery(
            'SELECT COUNT(r) FROM App\Entity\Response r'
        )->getSingleScalarResult();
    }

    public function getCharactersStats($direction) {

        return $this->manager->createQuery(
            'SELECT c.name, u.pseudo, u.image, SUM(c.defenseWon + c.attackWon) as totalWin
            FROM App\Entity\Fighter c
            JOIN c.owner u
            WHERE c.isActive = true
            GROUP BY c
            ORDER BY totalWin ' . $direction
        )->setMaxResults(5)->getResult();
    }
}
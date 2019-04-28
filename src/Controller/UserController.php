<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Pagination;
use App\Repository\FightRepository;
use App\Repository\FighterRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/leaderboard/{page<\d+>?1}", name="user_leaderboard")
     * @IsGranted("ROLE_USER")
     */
    public function leaderboard($page, Pagination $pagination) {

        $pagination->setEntityClass(User::class)
                   ->setCurrentPage($page)
                   ->setLimit(30)
                   ->setOrderBy(['score' => 'DESC']);
        return $this->render('user/leaderboard.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_show")
     * @IsGranted("ROLE_USER")
     */
    public function show(User $user, FightRepository $repo, FighterRepository $Frepo)
    {
        $fights = $repo->findFightsByUser($user->getId());
        $fighters = $Frepo->findBy([
            'owner' => $user,
            'isActive' => true
        ]);

        foreach($fights as $fight) {

            foreach($user->getFighters() as $fighter) {

                if($fighter == $fight->getOpponent()) {

                    $tempFighter = $fight->getFighter();
                    $fight->setFighter($fight->getOpponent());
                    $fight->setOpponent($tempFighter);
                    $fight->setIsWon(!$fight->getIsWon());
                }
            }
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'fights' => $fights,
            'fighters' => $fighters
        ]);
    }
}

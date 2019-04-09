<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FightRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show")
     * @IsGranted("ROLE_USER")
     */
    public function index(User $user, FightRepository $repo)
    {
        $fights = $repo->findFightsByUser($user->getId());

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
            'fights' => $fights
        ]);
    }
}

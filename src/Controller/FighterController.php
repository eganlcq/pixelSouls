<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Repository\FightRepository;
use App\Repository\WeaponRepository;
use App\Repository\FighterRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FighterController extends AbstractController
{
    /**
     * @Route("/fighters", name="fighters_index")
     */
    public function index(FighterRepository $repo)
    {
        $fighters = $repo->findAll();

        return $this->render('fighter/index.html.twig', [
            'fighters' => $fighters,
        ]);
    }

    /**
     * @Route("/fighters/{id}", name="fighters_show")
     */
    public function show(Fighter $fighter, WeaponRepository $Wrepo, FightRepository $Frepo) {

        $weapons = $Wrepo->findAll();
        $fights = $Frepo->findExistingFights($fighter->getId());

        return $this->render('fighter/show.html.twig', [
            'fighter'   => $fighter,
            'weapons'   => $weapons,
            'fights'    => $fights
        ]);
    }
}

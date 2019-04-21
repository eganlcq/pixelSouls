<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Service\Pagination;
use App\Repository\FightRepository;
use App\Repository\WeaponRepository;
use App\Repository\FighterRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FighterController extends AbstractController
{
    /**
     * @Route("/fighters/{page<\d+>?1}", name="fighters_index")
     * @IsGranted("ROLE_USER")
     */
    public function index(FighterRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Fighter::class)
                   ->setCurrentPage($page)
                   ->setLimit(12)
                   ->setCritera(['isActive' => true]);

        return $this->render('fighter/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/fighter/{id}", name="fighters_show")
     * @IsGranted("ROLE_USER")
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

    /**
     * @Route("/fighter/{id}/delete", name="fighters_delete")
     */
    public function delete(Fighter $fighter, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "<strong>{$fighter->getName()}</strong> has been deleted");
        $fighter->setIsActive(false);
        $manager->persist($fighter);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}

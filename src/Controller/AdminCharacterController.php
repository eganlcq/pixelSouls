<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Service\Pagination;
use App\Repository\FighterRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCharacterController extends AbstractController
{
    /**
     * @Route("/admin/characters/{page<\d+>?1}", name="admin_characters_index")
     */
    public function index(FighterRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Fighter::class)
                   ->setCurrentPage($page);
        return $this->render('admin/character/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/character/{id}/delete", name="admin_characters_delete")
     */
    public function delete(Fighter $fighter, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "Character n°<strong>{$fighter->getId()}</strong> was successfully deleted !");
        $manager->remove($fighter);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}

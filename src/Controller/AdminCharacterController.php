<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Service\Pagination;
use App\Form\SearchCharacterType;
use App\Repository\FighterRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCharacterController extends AbstractController
{
    /**
     * @Route("/admin/characters/{page<\d+>?1}", name="admin_characters_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(FighterRepository $repo, $page, Pagination $pagination, Request $request)
    {
        $pagination->setEntityClass(Fighter::class)
                   ->setCurrentPage($page);

        $form = $this->createForm(SearchCharacterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if($form->get('search')->getData() == null) {

                return $this->redirectToRoute('admin_characters_index');
            }
            else {

                return $this->redirectToRoute('admin_characters_search', [
                    'searchType' => $form->get('searchType')->getData(),
                    'search' => $form->get('search')->getData()
                ]);
            }
        }

        return $this->render('admin/character/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/characters/search/{searchType}/{search}", name="admin_characters_search")
     * @IsGranted("ROLE_ADMIN")
     */
    public function search(FighterRepository $repo, Request $request, $searchType, $search)
    {
        $form = $this->createForm(SearchCharacterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if($form->get('search')->getData() == null) {

                return $this->redirectToRoute('admin_characters_index');
            }
            else {

                return $this->redirectToRoute('admin_characters_search', [
                    'searchType' => $form->get('searchType')->getData(),
                    'search' => $form->get('search')->getData()
                ]);
            }
        }

        switch($searchType) {

            case 'Name':
                $fighters = $repo->searchByNameAdmin($search);
                break;
            case 'User':
                $fighters = $repo->searchByUserAdmin($search);
                break;
        }

        return $this->render('admin/character/search.html.twig', [
            'form' => $form->createView(),
            'data' => $fighters,
            'searchType' => $searchType,
            'search' => $search
        ]);
    }

    /**
     * @Route("/admin/character/{id}/delete", name="admin_characters_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Fighter $fighter, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "Character n°<strong>{$fighter->getId()}</strong> was successfully deleted !");
        $fighter->setIsActive(false);
        $manager->persist($fighter);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * @Route("/admin/character/{id}/activate", name="admin_characters_activate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activate(Fighter $fighter, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "Character n°<strong>{$fighter->getId()}</strong> was successfully reactivated !");
        $fighter->setIsActive(true);
        $manager->persist($fighter);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}

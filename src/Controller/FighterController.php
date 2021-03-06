<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Service\Pagination;
use App\Form\SearchCharacterType;
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
    public function index(FighterRepository $repo, $page, Pagination $pagination, Request $request)
    {
        $pagination->setEntityClass(Fighter::class)
                   ->setCurrentPage($page)
                   ->setLimit(12)
                   ->setCritera(['isActive' => true]);

        $form = $this->createForm(SearchCharacterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if($form->get('search')->getData() == null) {

                return $this->redirectToRoute('fighters_index');
            }
            else {

                return $this->redirectToRoute('fighters_search', [
                    'searchType' => $form->get('searchType')->getData(),
                    'search' => $form->get('search')->getData()
                ]);
            }
        }

        return $this->render('fighter/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/fighters/search/{searchType}/{search}", name="fighters_search")
     * @IsGranted("ROLE_USER")
     */
    public function search(FighterRepository $repo, Request $request, $searchType, $search) {

        $form = $this->createForm(SearchCharacterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if($form->get('search')->getData() == null) {

                return $this->redirectToRoute('fighters_index');
            }
            else {

                return $this->redirectToRoute('fighters_search', [
                    'searchType' => $form->get('searchType')->getData(),
                    'search' => $form->get('search')->getData()
                ]);
            }
        }

        switch($searchType) {

            case 'Name':
                $fighters = $repo->searchByName($search);
                break;
            case 'User':
                $fighters = $repo->searchByUser($search);
                break;
        }

        return $this->render('fighter/search.html.twig', [
            'data' => $fighters,
            'form' => $form->createView(),
            'searchType' => $searchType,
            'search' => $search
        ]);
    }

    /**
     * @Route("/fighter/{id}", name="fighters_show")
     * @IsGranted("ROLE_USER")
     */
    public function show(Fighter $fighter, WeaponRepository $Wrepo, FightRepository $Frepo, FighterRepository $FiRepo) {

        $weapons = $Wrepo->findAll();
        $fights = $Frepo->findExistingFights($fighter->getId());

        $user = $fighter->getOwner();
        $fighters = $FiRepo->findBy([
            'owner' => $user,
            'isActive' => true
        ]);

        return $this->render('fighter/show.html.twig', [
            'fighter'   => $fighter,
            'weapons'   => $weapons,
            'fights'    => $fights,
            'fighters' => $fighters
        ]);
    }

    /**
     * @Route("/fighter/{id}/delete", name="fighters_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Fighter $fighter, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "<strong>{$fighter->getName()}</strong> has been deleted");
        $fighter->setIsActive(false);
        $manager->persist($fighter);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}

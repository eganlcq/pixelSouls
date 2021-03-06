<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Service\Pagination;
use App\Form\SearchUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_users_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index($page, Pagination $pagination, Request $request)
    {
        $pagination->setEntityClass(User::class)
                   ->setCurrentPage($page);

        $form = $this->createForm(SearchUserType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if($form->get('search')->getData() == null) {

                return $this->redirectToRoute('admin_users_index');
            }
            else {

                return $this->redirectToRoute('admin_users_search', [
                    'searchType' => $form->get('searchType')->getData(),
                    'search' => $form->get('search')->getData()
                ]);
            }
        }

        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/search/{searchType}/{search}", name="admin_users_search")
     * @IsGranted("ROLE_ADMIN")
     */
    public function search(UserRepository $repo, Request $request, $searchType, $search)
    {
        $form = $this->createForm(SearchUserType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if($form->get('search')->getData() == null) {

                return $this->redirectToRoute('admin_users_index');
            }
            else {

                return $this->redirectToRoute('admin_users_search', [
                    'searchType' => $form->get('searchType')->getData(),
                    'search' => $form->get('search')->getData()
                ]);
            }
        }

        switch($searchType) {

            case 'Pseudo' :
                $users = $repo->searchByPseudo($search);
                break;
            case 'FirstName':
                $users = $repo->searchByFirstName($search);
                break;
            case 'LastName':
                $users = $repo->searchByLastName($search);
                break;
        }

        return $this->render('admin/user/search.html.twig', [
            'form' => $form->createView(),
            'data' => $users,
            'search' => $search
        ]);
    }

    /**
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(User $user, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if(!is_null($user->getImage())) {

                $file = $user->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $user->setImage($fileName);
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "User n°<strong>{$user->getId()}</strong> was successfully edited !");
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin_users_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(User $user, ObjectManager $manager) {

        $this->addFlash('success', "User n°<strong>{$user->getId()}</strong> was successfully deleted !");
        $user->setIsActive(false);
        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('admin_users_index');
    }

    /**
     * @Route("/admin/user/{id}/activate", name="admin_users_activate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activate(User $user, ObjectManager $manager) {

        $this->addFlash('success', "User n°<strong>{$user->getId()}</strong> was successfully activated !");
        $user->setIsActive(true);
        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('admin_users_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Service\Pagination;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_users_index")
     */
    public function index(UserRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(User::class)
                   ->setCurrentPage($page);
        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
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
     */
    public function delete(User $user, ObjectManager $manager) {

        $this->addFlash('success', "User n°<strong>{$user->getId()}</strong> was successfully deleted !");
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_users_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Response;
use App\Service\Pagination;
use App\Form\AdminResponseType;
use App\Repository\ResponseRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentsController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ResponseRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Response::class)
                   ->setCurrentPage($page);
        return $this->render('admin/comments/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Response $message, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdminResponseType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($message);
            $manager->flush();

            $this->addFlash('success', "Comment n°<strong>{$message->getId()}</strong> was successfully edited !");
        }

        return $this->render('admin/comments/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Response $message, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "Comment n°<strong>{$message->getId()}</strong> was successfully deleted !");
        $message->setIsActive(false);
        $manager->persist($message);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * @Route("/admin/comments/{id}/activate", name="admin_comments_activate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activate(Response $message, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "Comment n°<strong>{$message->getId()}</strong> was successfully reactivated !");
        $message->setIsActive(true);
        $manager->persist($message);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}

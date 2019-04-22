<?php

namespace App\Controller;

use App\Entity\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentsController extends AbstractController
{
    /**
     * @Route("comments/{id}/delete", name="comments_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Response $message, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "Your comment has been deleted");
        $message->setIsActive(false);
        $manager->persist($message);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}

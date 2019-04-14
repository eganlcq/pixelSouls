<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Form\AdminPostType;
use App\Repository\PostRepository;
use App\Repository\ResponseRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPostController extends AbstractController
{
    /**
     * @Route("/admin/posts", name="admin_posts_index")
     */
    public function index(PostRepository $repo)
    {
        return $this->render('admin/post/index.html.twig', [
            'posts' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/post/{id}/edit", name="admin_posts_edit")
     */
    public function edit(Post $post, ResponseRepository $repo, Request $request, ObjectManager $manager) {

        $post->setFirstMessage("Edit mode");
        $form = $this->createForm(AdminPostType::class, $post);
        $messages = $repo->findOrderedMessages($post->getId());

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($post);
            $manager->flush();
            $this->addFlash('success', "Post n°<strong>{$post->getId()}</strong> was successfully edited !");
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/admin/post/{id}/delete", name="admin_posts_delete")
     */
    public function delete(Post $post, ObjectManager $manager) {

        $this->addFlash('success', "Post n°<strong>{$post->getId()}</strong> was successfully deleted !");
        $manager->remove($post);
        $manager->flush();
        return $this->redirectToRoute('admin_posts_index');
    }
}

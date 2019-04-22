<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Response;
use App\Form\AdminPostType;
use App\Service\Pagination;
use App\Repository\PostRepository;
use App\Repository\ResponseRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPostController extends AbstractController
{
    /**
     * @Route("/admin/posts/{page<\d+>?1}", name="admin_posts_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(PostRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Post::class)
                   ->setCurrentPage($page);
        return $this->render('admin/post/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/post/{id}/edit/{page<\d+>?1}", name="admin_posts_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Post $post, ResponseRepository $repo, Request $request, ObjectManager $manager, $page, Pagination $pagination) {

        $post->setFirstMessage("Edit mode");
        $pagination->setEntityClass(Response::class)
                   ->setCurrentPage($page)
                   ->setLimit(10)
                   ->setCritera(['relatedPost' => $post])
                   ->setOrderBy(['createdAt' => 'DESC'])
                   ->setTemplatePath('post/pagination.html.twig');
        $form = $this->createForm(AdminPostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($post);
            $manager->flush();
            $this->addFlash('success', "Post n°<strong>{$post->getId()}</strong> was successfully edited !");
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/post/{id}/delete", name="admin_posts_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Post $post, ObjectManager $manager) {

        $this->addFlash('success', "Post n°<strong>{$post->getId()}</strong> was successfully deleted !");
        $post->setIsActive(false);
        $manager->persist($post);
        $manager->flush();
        return $this->redirectToRoute('admin_posts_index');
    }

    /**
     * @Route("/admin/post/{id}/activate", name="admin_posts_activate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activate(Post $post, ObjectManager $manager) {

        $this->addFlash('success', "Post n°<strong>{$post->getId()}</strong> was successfully reactivated !");
        $post->setIsActive(true);
        $manager->persist($post);
        $manager->flush();
        return $this->redirectToRoute('admin_posts_index');
    }
}

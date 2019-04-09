<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Response;
use App\Form\ResponseType;
use App\Repository\PostRepository;
use App\Repository\ResponseRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post_index")
     * @IsGranted("ROLE_USER")
     */
    public function index(PostRepository $repo, Request $request, ObjectManager $manager)
    {
        $posts = $repo->findOrderedPosts();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $response = new Response();
            $response->setWriter($user);
            $response->setContent($post->getFirstMessage());
            $manager->persist($response);

            $post->setWriter($user);
            $post->addResponse($response);
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute("post_show", [
                'id' => $post->getId()
            ]);
        }
        
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_show")
     * @IsGranted("ROLE_USER")
     */
    public function show(ResponseRepository $repo, Post $post, Request $request, ObjectManager $manager) {

        $messages = $repo->findOrderedMessages($post->getId());
        $message = new Response();
        $form = $this->createForm(ResponseType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $message->setWriter($this->getUser());
            $message->setRelatedPost($post);
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute("post_show", [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'messages' => $messages,
            'form' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Response;
use App\Form\ResponseType;
use App\Service\Pagination;
use App\Form\SearchPostType;
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
     * @Route("/posts/{page<\d+>?1}", name="post_index")
     * @IsGranted("ROLE_USER")
     */
    public function index(PostRepository $repo, Request $request, ObjectManager $manager, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Post::class)
                   ->setCurrentPage($page)
                   ->setLimit(20)
                   ->setCritera(['isActive' => true])
                   ->setOrderBy([
                       'createdAt' => 'DESC'
                   ]);
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

        $searchForm = $this->createForm(SearchPostType::class);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()) {

            if($searchForm->get('search')->getData() == null && $searchForm->get('typePost')->getData() == "All") {

                return $this->redirectToRoute('post_index');
            }
            else {

                return $this->redirectToRoute('post_search', [
                    'searchType' => $searchForm->get('searchType')->getData(),
                    'typePost' => $searchForm->get('typePost')->getData(),
                    'search' => $searchForm->get('search')->getData()
                ]);
            }
        }
        
        return $this->render('post/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/posts/search/{searchType}/{typePost}/{search}", name="post_search")
     * @IsGranted("ROLE_USER")
     */
    public function search(PostRepository $repo, Request $request, ObjectManager $manager, $searchType, $typePost, $search = null) {

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

        $searchForm = $this->createForm(SearchPostType::class);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()) {

            if($searchForm->get('search')->getData() == null && $searchForm->get('typePost')->getData() == "All") {

                return $this->redirectToRoute('post_index');
            }
            else {

                return $this->redirectToRoute('post_search', [
                    'searchType' => $searchForm->get('searchType')->getData(),
                    'typePost' => $searchForm->get('typePost')->getData(),
                    'search' => $searchForm->get('search')->getData()
                ]);
            }
        }

        if($search == null) {

            $posts = $repo->searchByType($typePost);
        }
        else {

            switch($searchType) {
    
                case 'Title':
                    if($typePost == "All") $posts = $repo->searchByTitle($search);
                    else $posts = $repo->searchByTitleAndType($search, $typePost);
                    break;
                case 'User':
                    if($typePost == "All") $posts = $repo->searchByUser($search);
                    else $posts = $repo->searchByUserAndType($search, $typePost);
                    break;
            }
        }
        
        return $this->render('post/search.html.twig', [
            'form' => $form->createView(),
            'searchForm' => $searchForm->createView(),
            'data' => $posts,
            'searchType' => $searchType,
            'search' => $search
        ]);
    }

    /**
     * @Route("/post/{id}/{page<\d+>?1}", name="post_show")
     * @IsGranted("ROLE_USER")
     */
    public function show(ResponseRepository $repo, Post $post, Request $request, ObjectManager $manager, $page, Pagination $pagination) {

        $pagination->setEntityClass(Response::class)
                   ->setCurrentPage($page)
                   ->setLimit(20)
                   ->setCritera(['relatedPost' => $post])
                   ->setOrderBy(['createdAt' => 'ASC'])
                   ->setTemplatePath('post/pagination.html.twig');
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
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/{id}/delete", name="post_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Post $post, ObjectManager $manager, Request $request) {

        $this->addFlash('success', "Your post has been deleted");
        $post->setIsActive(false);
        $manager->persist($post);
        $manager->flush();
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}

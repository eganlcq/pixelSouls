<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    /**
     * @Route("/", name="game")
     */
    public function index(UserRepository $repo)
    {

        return $this->render('game/index.html.twig');
        
        /*$encoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoder);

        $user = $repo->returnOneUser();
            
        $json = $serializer->serialize($user, 'json');
        return new Response($json);*/
    }

    /**
     * @Route("/request", name="request")
     */
    public function request(UserRepository $repo) {

        $users = $repo->findAll();

        return $this->json($users, 200, [], [
            ObjectNormalizer::ATTRIBUTES => [
                'id',
                'firstName',
                'lastName',
                'pseudo',
                'score'
            ]
        ]);
    }
}

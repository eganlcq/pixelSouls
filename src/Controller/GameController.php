<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Fighter;
use App\Repository\UserRepository;
use App\Repository\FighterRepository;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Persistence\ObjectManager;
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

    /**
     * @Route("/listCharacters", name="game_listCharacters")
     */
    public function listCharacter(UserRepository $userRepo, FighterRepository $fighterRepo) {

        $user = $userRepo->findOneBy([
            "firstName" => "Egan"
        ]);

        $fighters = $fighterRepo->findBy([
            "owner" => $user
        ]);

        return $this->json($fighters, 200, [], [
            ObjectNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'strength',
                'dexterity',
                'vitality',
                'level',
                'experience',
                'totalWin',
                'totalLoose',
                'experienceNeeded'
            ]
        ]);   
    }

    /**
     * @Route("/listOpponents", name="game_listOpponents")
     */
    public function listOpponents(UserRepository $userRepo, FighterRepository $fighterRepo) {

        $user = $userRepo->findOneBy([
            "firstName" => "Egan"
        ]);

        $fighters = $fighterRepo->findAllOpponents($user->getId());

        return $this->json($fighters, 200, [], [
            ObjectNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'strength',
                'dexterity',
                'vitality',
                'level',
                'experience',
                'totalWin',
                'totalLoose',
                'experienceNeeded',
                'ownerFullName',
                'ownerAvatar'
            ]
        ]); 
    }

    /**
     * @Route("/removeCharacter/{id}", name="game_deleteCharacter")
     */
    public function deleteCharacter(Fighter $fighter, ObjectManager $manager) {

        $manager->remove($fighter);
        $manager->flush();

        return $this->redirectToRoute('game_listCharacters');
    }
}

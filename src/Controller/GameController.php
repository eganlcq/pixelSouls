<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Weapon;
use App\Entity\Fighter;
use App\Repository\UserRepository;
use App\Repository\WeaponRepository;
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
                'experienceNeeded',
                'owner' => [
                    'id',
                    'pseudo',
                    'score',
                    'image',
                    'favoriteUsers' => [
                        'id'
                    ],
                    'fans' => [
                        'id'
                    ]
                ],
                'weapons' => [
                    'id',
                    'name',
                    'power',
                    'speed',
                    'parryChance',
                ]
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
                'owner' => [
                    'id',
                    'pseudo',
                    'score',
                    'image',
                ],
                'weapons' => [
                    'id',
                    'name',
                    'power',
                    'speed',
                    'parryChance'
                ]
            ]
        ]); 
    }

    /**
     * @Route("/listWeapons/{id}", name="game_listWeapons")
     */
    public function listWeapons(Fighter $fighter, WeaponRepository $repo) {

        $weapons = $repo->findRemainingWeapons($fighter->getId());

        return $this->json($weapons, 200, [], [
            ObjectNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'power',
                'speed',
                'parryChance'
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

    /**
     * @Route("/test", name="test")
     */
    public function test(UserRepository $repo) {

        $user = $repo->findOneBy([
            'firstName' => 'Egan'
        ]);

        return $this->json($user, 200, [], [
            ObjectNormalizer::ATTRIBUTES => [
                'id',
                'pseudo',
                'score',
                'image',
                'favoriteUsers' => [
                    'id'
                ],
                'fans' => [
                    'id'
                ]
            ]
        ]); 
    }

    /**
     * @Route("/updateFighter/{id}", name="updateFighter")
     */
    public function updateFighter(Fighter $fighter, ObjectManager $manager) {

        if(isset($_POST['json'])) {

            $json = $_POST['json'];
            $data = json_decode($json);
            $fighter->setExperience($data->experience);
            $fighter->setLevel($data->level);
            $fighter->setStrength($data->strength);
            $fighter->setDexterity($data->dexterity);
            $fighter->setVitality($data->vitality);
            $manager->merge($fighter);
            $manager->flush();
        }

        return new Response();
    }
    /**
     * @Route("/addWeapon/{id}", name="addWeapon")
     */
    public function addWeapon(Fighter $fighter, WeaponRepository $repo, ObjectManager $manager) {

        if(isset($_POST['json'])) {

            $json = $_POST['json'];
            $data = json_decode($json);
            $weapon = $repo->findOneBy(['name'=> $data->name]);
            $fighter->addWeapon($weapon);
            $manager->persist($fighter);
            $manager->flush();
        }

        return new Response();
    }

    /**
     * @Route("/newCharacter", name="newCharacter")
     */
    public function addFighter(UserRepository $repo, ObjectManager $manager) {

        if(isset($_POST['json'])) {

            $user = $repo->findOneBy([
                "firstName" => "Egan"
            ]);

            $json = $_POST['json'];
            $data = json_decode($json);

            $fighter = new Fighter();
            $fighter->setOwner($user)
                    ->setName($data->name)
                    ->setImage("http://127.0.0.1:8000/img/anonym.png");
            
            $manager->persist($fighter);
            $manager->flush();
        }

        return new Response();
    }
}

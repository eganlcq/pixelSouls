<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Fight;
use App\Entity\Weapon;
use App\Entity\Fighter;
use App\Service\ErrorHandler;
use App\Repository\UserRepository;
use App\Repository\WeaponRepository;
use App\Repository\FighterRepository;
use App\Repository\PatchNoteRepository;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    /**
     * @Route("/", name="game")
     * @IsGranted("ROLE_USER")
     */
    public function index(PatchNoteRepository $repo)
    {
        $patchNotes = $repo->findAll();

        if($this->getUser()) {

            if(!$this->getUser()->getIsActive()) {

                $cache = new FilesystemCache();
                $cache->set("error", "Your account is inactive, please check your email to activate it");
                return $this->redirectToRoute('account_logout');
            }
        }
        return $this->render('game/index.html.twig', [
            'patchNotes' => $patchNotes
        ]);
    }

    /**
     * @Route("/listCharacters/{token}/{tempToken}", name="game_listCharacters")
     */
    public function listCharacter(FighterRepository $fighterRepo, $token, UserRepository $userRepo, $tempToken = "") {

        if(isset($_POST["token"]) && $_POST["token"] == $token) {

            $user = $this->getUser();

            if($user == null) {

                if($tempToken != "") $user = $userRepo->findOneByTempToken($tempToken);
                else $user = $userRepo->findOneByPseudo("Octofen");
            }
    
            $fighters = $fighterRepo->findBy([
                "owner" => $user,
                "isActive" => true
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
                    'defenseWon',
                    'defenseLost',
                    'attackWon',
                    'attackLost',
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
    }

    /**
     * @Route("/listOpponents/{token}/{tempToken}", name="game_listOpponents")
     */
    public function listOpponents(FighterRepository $fighterRepo, $token, UserRepository $userRepo, $tempToken = "") {

        if(isset($_POST["token"]) && $_POST["token"] == $token) {

            $user = $this->getUser();

            if($user == null) {

                if($tempToken != "") $user = $userRepo->findOneByTempToken($tempToken);
                else $user = $userRepo->findOneByPseudo("Octofen");
            }
    
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
                    'defenseWon',
                    'defenseLost',
                    'attackWon',
                    'attackLost',
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
    }

    /**
     * @Route("/listWeapons/{id}/{token}", name="game_listWeapons")
     */
    public function listWeapons(Fighter $fighter, WeaponRepository $repo, $token) {

        if(isset($_POST["token"]) && $_POST["token"] == $token) {

            $weaponOfFighter = $fighter->getWeapons()->toArray();
            $weapons;

            if(sizeof($fighter->getWeapons()->toArray()) > 0) {

                $weapons = $repo->findRemainingWeapons($fighter->getId());
            }
            else {

                $weapons = $repo->findAll();
            }

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
    }

    /**
     * @Route("/removeCharacter/{id}/{token}/{tempToken}", name="game_deleteCharacter")
     */
    public function deleteCharacter(Fighter $fighter, ObjectManager $manager, FighterRepository $fighterRepo, $token, UserRepository $userRepo, $tempToken = "") {

        if(isset($_POST["token"]) && $_POST["token"] == $token) {

            $fighter->setIsActive(false);
            $manager->persist($fighter);
            $manager->flush();
    
            $user = $this->getUser();

            if($user == null) {

                if($tempToken != "") $user = $userRepo->findOneByTempToken($tempToken);
                else $user = $userRepo->findOneByPseudo("Octofen");
            }
    
            $fighters = $fighterRepo->findBy([
                "owner" => $user,
                "isActive" => true
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
                    'defenseWon',
                    'defenseLost',
                    'attackWon',
                    'attackLost',
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
    }

    /**
     * @Route("/updateFighter/{token}/{tempToken}", name="updateFighter")
     */
    public function updateFighter(ObjectManager $manager, FighterRepository $repo, $token, UserRepository $userRepo, $tempToken = "") {

        if(isset($_POST["token"]) && $_POST["token"] == $token) {

            if(isset($_POST['json'])) {
    
                $json = $_POST['json'];
                $data = json_decode($json);
                $fighterData = $data->characterChosen;
                $opponentData = $data->opponentChosen;
                $fighter = $repo->findOneById($fighterData->id);
                $opponent = $repo->findOneById($opponentData->id);
                $user = $this->getUser();

                if($user == null) {

                    if($tempToken != "") $user = $userRepo->findOneByTempToken($tempToken);
                    else $user = $userRepo->findOneByPseudo("Octofen");
                }

                $fight = new Fight();
                $fighter->setExperience($fighterData->experience);
                $fighter->setLevel($fighterData->level);
                $fighter->setStrength($fighterData->strength);
                $fighter->setDexterity($fighterData->dexterity);
                $fighter->setVitality($fighterData->vitality);
                $fighter->setAttackWon($fighterData->attackWon);
                $fighter->setAttackLost($fighterData->attackLost);
                $opponent->setDefenseWon($opponentData->defenseWon);
                $opponent->setDefenseLost($opponentData->defenseLost);
                $user->setScore($fighterData->owner->score);
                $fight->setFighter($fighter);
                $fight->setOpponent($opponent);
                $fight->setIsWon($data->isWon);
                $manager->merge($fighter);
                $manager->merge($opponent);
                $manager->merge($user);
                $manager->merge($fight);
                $manager->flush();
            }
    
            return new Response();
        }
    }

    /**
     * @Route("/addWeapon/{id}/{token}", name="addWeapon")
     */
    public function addWeapon(Fighter $fighter, WeaponRepository $repo, ObjectManager $manager, $token) {

        if(isset($_POST["token"]) && $_POST["token"] == $token) {

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
    }

    /**
     * @Route("/newCharacter/{token}/{tempToken}", name="newCharacter")
     */
    public function addFighter(ObjectManager $manager, $token, UserRepository $userRepo, $tempToken = "") {

        if(isset($_POST["token"]) && $_POST["token"] == $token) {

            if(isset($_POST['json'])) {
    
                $user = $this->getUser();
                
                if($user == null) {

                    if($tempToken != "") $user = $userRepo->findOneByTempToken($tempToken);
                    else $user = $userRepo->findOneByPseudo("Octofen");
                }
    
                $json = $_POST['json'];
                $data = json_decode($json);
    
                $fighter = new Fighter();
                $fighter->setOwner($user)
                        ->setName($data->name)
                        ->setImage("anonym.png");
                
                $manager->persist($fighter);
                $manager->flush();
            }
    
            return new Response();
        }
    }
}

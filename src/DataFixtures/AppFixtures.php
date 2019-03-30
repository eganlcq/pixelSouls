<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Armor;
use App\Entity\Fight;
use App\Entity\Weapon;
use App\Entity\Fighter;
use App\Entity\Response;
use App\Entity\TypePost;
use App\Entity\Achievement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $faker;

    private $encoder;
    private $listUser;
    private $listAchievement;
    private $listRole;
    private $listWeapon;
    private $listArmor;
    private $listFighter;
    private $listTypePost;

    public function __construct(UserPasswordEncoderInterface $encoder) {

        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->faker            = Factory::create('fr-FR');

        $this->listUser         = [];
        $this->listAchievement  = [];
        $this->listRole         = [];
        $this->listWeapon       = [];
        $this->listArmor        = [];
        $this->listFighter      = [];
        $this->listTypePost     = [];

        $url = 'http://127.0.0.1:8000/img/anonym.png';

        // CREATION DES UTILISATEURS
        $this->createUser(
            'Egan',
            'Lecocq',
            'Octofen',
            $url
        )
        ->createUser(
            'Luca',
            'Mellini',
            'Ichikoros',
            $url
        )
        ->createUser(
            'Jordan',
            'Vankeerberghen',
            'Tanckraa',
            $url
        )
        ->createUser(
            'Guillaume',
            'Wyart',
            'Maestro700',
            $url
        )
        ->createUser(
            'Nicolas',
            'Roasar',
            'Kidnico',
            $url
        )
        ->createUser(
            'David',
            'Jacobs',
            'Pesto',
            $url
        )
        ->createUser(
            'Daniel',
            'Vicente',
            'Kaneda',
            $url
        )
        ->createUser(
            'Remy',
            'Serrano',
            'DekaLeDon',
            $url
        )
        ->createUser(
            'Maxime',
            'Cochez',
            'Mako',
            $url
        )
        ->createUser(
            'Juliano',
            'Celestri',
            'Lawlieteru',
            $url
        );

        // CREATION DES SUCCES
        $this->createAchievement(
            "Level 5",
            $url,
            "Reach level 5"
        )
        ->createAchievement(
            "Level 10",
            $url,
            "Reach level 10"
        )
        ->createAchievement(
            "Level 15",
            $url,
            "Reach level 15"
        )
        ->createAchievement(
            "Level 20",
            $url,
            "Reach level 20"
        )
        ->createAchievement(
            "Overpowered",
            $url,
            "Reach level 20 with one character"
        )
        ->createAchievement(
            "Diabolic duo",
            $url,
            "Reach level 20 with 2 character"
        )
        ->createAchievement(
            "Terrific trio",
            $url,
            "Reach level 20 with 3 character"
        )
        ->createAchievement(
            "Quintessential Quintet",
            $url,
            "Reach level 20 with 5 character"
        )
        ->createAchievement(
            "I won ? I won !!",
            $url,
            "Win against 1 player who attacked you"
        )
        ->createAchievement(
            "Defender",
            $url,
            "Win against 20 player who attacked you"
        )
        ->createAchievement(
            "You shall not pass",
            $url,
            "Win against 50 players who attacked you"
        )
        ->createAchievement(
            "Know your ennemy",
            $url,
            "Win against 1 player you attacked"
        )
        ->createAchievement(
            "Slayer",
            $url,
            "Win against 20 players you attacked"
        )
        ->createAchievement(
            "Conqueror",
            $url,
            "Win against 50 players you attacked"
        )
        ->createAchievement(
            "Wrath of gods",
            $url,
            "Win against 200 players in total"
        )
        ->createAchievement(
            "Polyvalent",
            $url,
            "Own all weapons"
        )
        ->createAchievement(
            "Fortress",
            $url,
            "Own all armors"
        );

        // CREATION DES ROLES
        $this->createRole('ROLE_USER')
             ->createRole('ROLE_ADMIN');

        // CREATION DES ARMES
        $this->createWeapon(
            "Dagger",
            'http://127.0.0.1:8000/img/weapons/dagger.png',
            65,
            100,
            10,
            "Small dagger, lack of power but very fast due to to its light weight, a little chance to parry"
        )
        ->createWeapon(
            "Sword",
            'http://127.0.0.1:8000/img/weapons/sword.png',
            110,
            70,
            5,
            "Balanced weapon, inflicting standard damage, very little chance to parry"
        )
        ->createWeapon(
            "Axe",
            'http://127.0.0.1:8000/img/weapons/axe.png',
            125,
            60,
            0,
            "Inflict good damage but a bit slow"
        )
        ->createWeapon(
            "Hammer",
            'http://127.0.0.1:8000/img/weapons/hammer.png',
            115,
            65,
            0,
            "Common strike weapon, good damage but a bit slow"
        )
        ->createWeapon(
            "Greatsword",
            'http://127.0.0.1:8000/img/weapons/greatsword.png',
            140,
            50,
            0,
            "Large weapon wielded with two hands, slow but powerful"
        )
        ->createWeapon(
            "Greataxe",
            'http://127.0.0.1:8000/img/weapons/greataxe.png',
            190,
            30,
            0,
            "Axe requiring inhuman strenght to wield it, really powerful but very slow"
        )
        ->createWeapon(
            "Greathammer",
            'http://127.0.0.1:8000/img/weapons/greathammer.png',
            170,
            40,
            0,
            "Large hammer dealing extremely heavy strike attacks, really slow"
        )
        ->createWeapon(
            "Curved sword",
            'http://127.0.0.1:8000/img/weapons/curvedBlade.png',
            90,
            90,
            40,
            "Small curved sword that excels in swift movement and very fast attacks, good chances to parry"
        )
        ->createWeapon(
            "Estoc",
            'http://127.0.0.1:8000/img/weapons/estoc.png',
            105,
            70,
            0,
            "Large thrusting sword used for dealing piercing damages"
        )
        ->createWeapon(
            "Halberd",
            'http://127.0.0.1:8000/img/weapons/halberd.png',
            125,
            60,
            30,
            "Long-hilted weapon mixing spear and axe, chance to parry"
        )
        ->createWeapon(
            "Katana",
            'http://127.0.0.1:8000/img/weapons/katana.png',
            115,
            70,
            30,
            "Weapon with finely-sharpened blade who can cut flesh like butter, chance to parry"
        )
        ->createWeapon(
            "Scythe",
            'http://127.0.0.1:8000/img/weapons/scythe.png',
            115,
            60,
            20,
            "Normally used for harvesting, but its sharp curved blade can be used for battle as well"
        )
        ->createWeapon(
            "Mace",
            'http://127.0.0.1:8000/img/weapons/cudgel.png',
            110,
            80,
            0,
            "Simple wooden club, pretty fast for standard damage"
        )
        ->createWeapon(
            "Spear",
            'http://127.0.0.1:8000/img/weapons/spear.png',
            105,
            60,
            40,
            "Thrusting weapon, standard damage and low speed but high chance to parry"
        )
        ->createWeapon(
            "Bo",
            'http://127.0.0.1:8000/img/weapons/bo.png',
            95,
            80,
            25,
            "Long piece of wood, used like a spear, deal less damage but faster"
        )
        ->createWeapon(
            "Twin blades",
            'http://127.0.0.1:8000/img/weapons/guandao.png',
            125,
            70,
            0,
            "Stick with blades on both sides of it"
        )
        ->createWeapon(
            "Sai",
            'http://127.0.0.1:8000/img/weapons/sai.png',
            60,
            90,
            60,
            "Weapon sized like a dagger with greatly curved guard, very high chances to parry"
        )
        ->createWeapon(
            "Tonfa",
            'http://127.0.0.1:8000/img/weapons/tonfa.png',
            80,
            90,
            40,
            "Weapon with grip on the side, high chances to parry"
        )
        ->createWeapon(
            "Fork",
            'http://127.0.0.1:8000/img/weapons/fork.png',
            105,
            60,
            0,
            "Not originally intended for battle, but can deal decent damages"
        )
        ->createWeapon(
            "Frying pan",
            'http://127.0.0.1:8000/img/weapons/pan.png',
            90,
            80,
            0,
            "It reminds me something..."
        );

        // CREATION DES ARMURES
        $this->createArmor(
            "Rags",
            $url,
            3,
            2,
            0,
            "A terribly worn shirt"
        )
        ->createArmor(
            "Chainmail armor",
            $url,
            11,
            9,
            0,
            "Armor made of thin interlinking rings of steel"
        )
        ->createArmor(
            "Knight armor",
            $url,
            13,
            11,
            0,
            "Armor of a knight made of solid iron"
        )
        ->createArmor(
            "Fire armor",
            $url,
            4,
            4,
            13,
            "Armor made of fire with bronze ornament"
        )
        ->createArmor(
            "Cloth",
            $url,
            4,
            4,
            0,
            "Basic cloths"
        )
        ->createArmor(
            "Iron armor",
            $url,
            12,
            9,
            0,
            "Armor made of iron"
        )
        ->createArmor(
            "Light armor",
            $url,
            11,
            9,
            10,
            "Chainmail armor blessed with pure light"
        )
        ->createArmor(
            "Dress",
            $url,
            4,
            4,
            0,
            "Regular dress"
        )
        ->createArmor(
            "Dress",
            $url,
            4,
            4,
            0,
            "Regular dress"
        )
        ->createArmor(
            "Shell armor",
            $url,
            18,
            17,
            0,
            "Armor made of shells"
        )
        ->createArmor(
            "Copper armor",
            $url,
            13,
            11,
            0,
            "Armor made of copper"
        )
        ->createArmor(
            "Titanium armor",
            $url,
            19,
            23,
            0,
            "Armor made of titanium"
        )
        ->createArmor(
            "Steel armor",
            $url,
            12,
            9,
            0,
            "Armor made of steel"
        )
        ->createArmor(
            "Ice armor",
            $url,
            13,
            12,
            9,
            "Armor made of ice"
        )
        ->createArmor(
            "Gold armor",
            $url,
            14,
            14,
            0,
            "Armor made of gold"
        )
        ->createArmor(
            "Silver armor",
            $url,
            13,
            15,
            0,
            "Armor made of silver"
        )
        ->createArmor(
            "Dark armor",
            $url,
            13,
            9,
            9,
            "Armor made of dark"
        )
        ->createArmor(
            "Stone armor",
            $url,
            19,
            22,
            0,
            "Armor made of stone"
        )
        ->createArmor(
            "Leather armor",
            $url,
            7,
            5,
            0,
            "Armor made of leather"
        )
        ->createArmor(
            "Rusty armor",
            $url,
            14,
            13,
            0,
            "Rusty armor"
        )
        ->createArmor(
            "Spiked armor",
            $url,
            11,
            19,
            13,
            "Armor made of spikes"
        );

        // CREATION DES TYPES DE POST
        $this->createTypePost("Discussion")
             ->createTypePost("Request")
             ->createTypePost("Technical problems");

        foreach($this->listUser as $user) {

            for($i = 1; $i < $this->faker->numberBetween(0, 20); $i++) {

                $otherUser = $this->listUser[$this->faker->numberBetween(0, count($this->listUser) - 1)];
                

                $user->addFavoriteUser($otherUser);
                $otherUser->addFan($user);
            }

            for($i = 1; $i < $this->faker->numberBetween(0, 10); $i++) {

                $achievement = $this->listAchievement[$this->faker->numberBetween(0, count($this->listAchievement) - 1)];

                $user->addAchievement($achievement);
            }

            if($user->getPseudo() === "Octofen") {

                foreach($this->listRole as $role) {

                    $user->addCurrentRole($role);
                }
            }
            else {

                $user->addCurrentRole($this->listRole[0]);
            }

            // CREATION DES POSTS
            for($i = 1; $i <= $this->faker->numberBetween(0, 10); $i++) {

                $post = $this->createPost($user);
                $manager->persist($post);

                // CREATION DES REPONSES
                for($i = 1; $i <= $this->faker->numberBetween(0, 10); $i++) {

                    $response = $this->createResponse($post);
                    $manager->persist($response);
                }
            }

            // CREATION DES PERSONNAGES
            for($i = 1; $i <= $this->faker->numberBetween(3, 6); $i++) {

                $fighter = $this->createFighter($user, $url);

                // CREATION DES COMBATS
                for($i = 1; $i < mt_rand(0, 10); $i++) {

                    $fight = $this->createFight($fighter);
                    $manager->persist($fight);
                }

                for($i = 1; $i < $this->faker->numberBetween(0, 10); $i++) {

                    $weapon = $this->listWeapon[$this->faker->numberBetween(0, count($this->listWeapon) - 1)];
    
                    $fighter->addWeapon($weapon);
                }

                for($i = 1; $i < $this->faker->numberBetween(0, 10); $i++) {

                    $armor = $this->listArmor[$this->faker->numberBetween(0, count($this->listArmor) - 1)];
    
                    $fighter->addArmor($armor);
                }
                
                $manager->persist($fighter);
            }
        }

        foreach($this->listUser as $user) {

            $manager->persist($user);
        }

        foreach($this->listAchievement as $achievement) {

            $manager->persist($achievement);
        }

        foreach($this->listRole as $role) {

            $manager->persist($role);
        }

        foreach($this->listWeapon as $weapon) {

            $manager->persist($weapon);
        }

        foreach($this->listArmor as $armor) {

            $manager->persist($armor);
        }

        foreach($this->listTypePost as $typePost) {

            $manager->persist($typePost);
        }

        $manager->flush();
    }

    private function createUser($firstName, $lastName, $pseudo, $image) {

        $user = new User();

        $hash = $this->encoder->encodePassword($user, 'password');

        $user->setFirstName($firstName)
             ->setLastName($lastName)
             ->setPseudo($pseudo)
             ->setHash($hash)
             ->setImage($image);

        $this->listUser[] = $user;

        return $this;
    }

    private function createAchievement($name, $image, $description) {

        $achievement = new Achievement();

        $achievement->setName($name)
                    ->setImage($image)
                    ->setDescription($description);

        $this->listAchievement[] = $achievement;

        return $this;
    }

    private function createRole($name) {

        $role = new Role();

        $role->setName($name);

        $this->listRole[] = $role;

        return $this;
    }

    private function createPost($user) {

        $post = new Post();

        $post->setTitle($this->faker->sentence())
             ->setContent($this->faker->paragraph())
             ->setWriter($user)
             ->setType($this->listTypePost[$this->faker->numberBetween(0, count($this->listTypePost) - 1)]);

        return $post;
    }

    private function createTypePost($name) {

        $typePost = new TypePost();

        $typePost->setName($name);

        $this->listTypePost[] = $typePost;

        return $this;
    }

    private function createResponse($post) {

        $response = new Response();

        $response->setContent($this->faker->paragraph())
                 ->setRelatedPost($post)
                 ->setWriter($this->listUser[$this->faker->numberBetween(0, count($this->listUser) - 1)]);

        return $response;
    }

    private function createWeapon($name, $image, $power, $speed, $parryChance, $description) {

        $weapon = new Weapon();

        $weapon->setName($name)
               ->setImage($image)
               ->setPower($power)
               ->setSpeed($speed)
               ->setParryChance($parryChance)
               ->setDescription($description);

        $this->listWeapon[] = $weapon;

        return $this;
    }

    private function createArmor($name, $image, $defense, $weight, $damage, $description) {

        $armor = new Armor();

        $armor->setName($name)
              ->setImage($image)
              ->setDefense($defense)
              ->setWeight($weight)
              ->setDamage($damage)
              ->setDescription($description);

        $this->listArmor[] = $armor;

        return $this;
    }

    private function createFighter($user, $image) {

        $fighter = new Fighter();

        $fighter->setOwner($user)
                ->setName($this->faker->lastName())
                ->setImage($image);

        $this->listFighter[] = $fighter;

        return $fighter;
    }

    private function createFight($fighter) {

        $fight = new Fight();

        $fight->setFighter($fighter)
              ->setOpponent($this->listFighter[$this->faker->numberBetween(0, count($this->listFighter) - 1)]);

        return $fight;
    }
}

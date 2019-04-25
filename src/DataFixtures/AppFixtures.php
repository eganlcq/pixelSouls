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
    private $manager;

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

        $this->manager = $manager;

        $url = 'anonym.png';

        // CREATION DES ARMES
        $this->createWeapon(
            "Dagger",
            'dagger.png',
            12,
            30,
            10,
            "Small dagger, lack of power but very fast due to to its light weight, a little chance to parry"
        )
        ->createWeapon(
            "Sword",
            'sword.png',
            15,
            11,
            5,
            "Balanced weapon, inflicting standard damage, very little chance to parry"
        )
        ->createWeapon(
            "Axe",
            'axe.png',
            16,
            9,
            0,
            "Inflict good damage but a bit slow"
        )
        ->createWeapon(
            "Hammer",
            'hammer.png',
            18,
            8,
            0,
            "Common strike weapon, good damage but a bit slow"
        )
        ->createWeapon(
            "Greatsword",
            'greatsword.png',
            20,
            7,
            0,
            "Large weapon wielded with two hands, slow but powerful"
        )
        ->createWeapon(
            "Greataxe",
            'greataxe.png',
            25,
            6,
            0,
            "Axe requiring inhuman strenght to wield it, really powerful but very slow"
        )
        ->createWeapon(
            "Greathammer",
            'greathammer.png',
            30,
            5,
            0,
            "Large hammer dealing extremely heavy strike attacks, really slow"
        )
        ->createWeapon(
            "Curved sword",
            'curvedBlade.png',
            15,
            15,
            40,
            "Small curved sword that excels in swift movement and very fast attacks, good chances to parry"
        )
        ->createWeapon(
            "Estoc",
            'estoc.png',
            12,
            17,
            0,
            "Large thrusting sword used for dealing piercing damages"
        )
        ->createWeapon(
            "Halberd",
            'halberd.png',
            18,
            8,
            30,
            "Long-hilted weapon mixing spear and axe, chance to parry"
        )
        ->createWeapon(
            "Katana",
            'katana.png',
            15,
            20,
            30,
            "Weapon with finely-sharpened blade who can cut flesh like butter, chance to parry"
        )
        ->createWeapon(
            "Scythe",
            'scythe.png',
            20,
            7,
            20,
            "Normally used for harvesting, but its sharp curved blade can be used for battle as well"
        )
        ->createWeapon(
            "Mace",
            'cudgel.png',
            17,
            9,
            0,
            "Simple wooden club, pretty fast for standard damage"
        )
        ->createWeapon(
            "Spear",
            'spear.png',
            13,
            11,
            40,
            "Thrusting weapon, standard damage and low speed but high chance to parry"
        )
        ->createWeapon(
            "Bo",
            'bo.png',
            12,
            20,
            25,
            "Long piece of wood, used like a spear, deal less damage but faster"
        )
        ->createWeapon(
            "Twin blades",
            'guandao.png',
            20,
            8,
            0,
            "Stick with blades on both sides of it"
        )
        ->createWeapon(
            "Sai",
            'sai.png',
            14,
            30,
            60,
            "Weapon sized like a dagger with greatly curved guard, very high chances to parry"
        )
        ->createWeapon(
            "Tonfa",
            'tonfa.png',
            13,
            25,
            40,
            "Weapon with grip on the side, high chances to parry"
        )
        ->createWeapon(
            "Fork",
            'fork.png',
            12,
            7,
            0,
            "Not originally intended for battle, but can deal decent damages"
        )
        ->createWeapon(
            "Frying pan",
            'pan.png',
            11,
            11,
            0,
            "It reminds me something..."
        );

        // CREATION DES UTILISATEURS
        $this->createUser(
            'BOT',
            '1',
            'BOT1',
            $url,
            'BOT1@hotmail.com',
            1,
            100,
            100,
            100
        )
        ->createUser(
            'BOT',
            '3',
            'BOT3',
            $url,
            'BOT3@hotmail.com',
            3,
            130,
            130,
            130
        )
        ->createUser(
            'BOT',
            '5',
            'BOT5',
            $url,
            'BOT5@hotmail.com',
            5,
            150,
            150,
            150
        )
        ->createUser(
            'BOT',
            '8',
            'BOT8',
            $url,
            'BOT8@hotmail.com',
            8,
            180,
            180,
            180
        )
        ->createUser(
            'BOT',
            '10',
            'BOT10',
            $url,
            'BOT10@hotmail.com',
            10,
            200,
            200,
            200
        )
        ->createUser(
            'BOT',
            '12',
            'BOT12',
            $url,
            'BOT12@hotmail.com',
            12,
            220,
            220,
            220
        )
        ->createUser(
            'BOT',
            '15',
            'BOT15',
            $url,
            'BOT15@hotmail.com',
            15,
            250,
            250,
            250
        )
        ->createUser(
            'BOT',
            '20',
            'BOT20',
            $url,
            'BOT20@hotmail.com',
            20,
            300,
            300,
            300
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

            $user->addCurrentRole($this->listRole[0]);
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

    private function createUser($firstName, $lastName, $pseudo, $image, $email, $level, $strength, $dexterity, $vitality) {

        $user = new User();

        $hash = $this->encoder->encodePassword($user, 'password');

        $user->setFirstName($firstName)
             ->setLastName($lastName)
             ->setPseudo($pseudo)
             ->setHash($hash)
             ->setImage($image)
             ->setEmail($email)
             ->setIsActive(true);

        for($i = 0; $i < 3; $i++) {

            $user->addFighter($this->createFighter($image, $level, $strength, $dexterity, $vitality));
        }

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

    private function createFighter($image, $level, $strength, $dexterity, $vitality) {

        $fighter = new Fighter();

        $fighter->setName($this->faker->lastName())
                ->setImage($image)
                ->setLevel($level)
                ->setStrength($strength)
                ->setDexterity($dexterity)
                ->setVitality($vitality);

        for($i = 1; $i < $level; $i++) {

            $weapon = $this->listWeapon[$this->faker->numberBetween(0, count($this->listWeapon) - 1)];

            $fighter->addWeapon($weapon);
        }

        $this->listFighter[] = $fighter;
        $this->manager->persist($fighter);

        return $fighter;
    }

    private function createFight() {

        $fight = new Fight();

        $fight->setFighter($this->listFighter[$this->faker->numberBetween(0, count($this->listFighter) - 1)])
              ->setOpponent($this->listFighter[$this->faker->numberBetween(0, count($this->listFighter) - 1)])
              ->setIsWon((bool) mt_rand(0, 1));

        return $fight;
    }
}

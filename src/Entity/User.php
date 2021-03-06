<?php

namespace App\Entity;

use App\Service\RandomString;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"pseudo"},
 *  message="This nickname is already used"
 * )
 * @UniqueEntity(
 *  fields={"email"},
 *  message="This email is already used"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="You have to give us a firstName")
     * @Assert\Length(min=2, minMessage="There is no firstName with less than 2 characters")
     * @Assert\Length(max=20, maxMessage="Even if your firstName is longer than 20 characters, try shorten it")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="You have to give us a lastName")
     * @Assert\Length(min=2, minMessage="There is no lastName with less than 2 characters")
     * @Assert\Length(max=20, maxMessage="Even if your lastName is longer than 20 characters, try shorten it")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="You have to give us a nickname")
     * @Assert\Length(min=3, minMessage="Your nickname should at least have 3 characters")
     * @Assert\Length(max=10, maxMessage="Your nickname shouldn't have more than 10 characters")
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="You have to give us a password")
     * @Assert\Length(min=8, minMessage="Your password should have at least 8 characters, for your safety")
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Both passwords doesn't match")
     */
    private $confirmPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(
     * maxSize = "20M",
     * maxSizeMessage = "Your file is too large, please take a lighter one",
     * mimeTypesMessage = "The file format is incorrect"
     * )
     * 
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="fans")
     */
    private $favoriteUsers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="favoriteUsers")
     */
    private $fans;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Achievement", mappedBy="owners")
     */
    private $achievements;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $currentRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="writer", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Response", mappedBy="writer", orphanRemoval=true)
     */
    private $responses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Fighter", mappedBy="owner", orphanRemoval=true)
     */
    private $fighters;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Email invalide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tempToken;

    public function __construct()
    {
        $this->favoriteUsers = new ArrayCollection();
        $this->fans = new ArrayCollection();
        $this->achievements = new ArrayCollection();
        $this->currentRoles = new ArrayCollection();
        $this->messagesSent = new ArrayCollection();
        $this->messagesReceived = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->responses = new ArrayCollection();
        $this->fighters = new ArrayCollection();
    }

     /**
     * @ORM\PrePersist
     */
    public function prePersist() {

        if(empty($this->score)) {

            $this->score = 1000;
        }
        if(empty($this->token)) {

            $this->token = RandomString::Generate($this->pseudo);
        }
        if(empty($this->image)) {

            $this->image = 'anonym.png';
        }
        if(empty($this->isActive)) {

            $this->isActive = false;
        }
    }

    public function getTotalWin() {

        $value = 0;

        foreach($this->fighters as $fighter) {

            $value += $fighter->getTotalWin();
        }

        return $value;
    }

    public function getTotalLoose() {

        $value = 0;

        foreach($this->fighters as $fighter) {

            $value += $fighter->getTotalLoose();
        }

        return $value;
    }
    
    public function getFullName() {

        return $this->firstName . ' ' . $this->lastName;
    }

    public function isAdmin() {

        foreach($this->getRoles() as $role) {

            if($role == "ROLE_ADMIN") return true;
        }

        return false;
    }

    public function getRoles() {

        $roles = $this->currentRoles->map(function($role){

            return $role->getName();
        })->toArray();
        return $roles;
    }

    public function getPassword() {

        return $this->hash;
    }

    public function getSalt() {}

    public function getUsername() {

        return $this->email;
    }

    public function eraseCredentials() {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFavoriteUsers(): Collection
    {
        return $this->favoriteUsers;
    }

    public function addFavoriteUser(self $favoriteUser): self
    {
        if (!$this->favoriteUsers->contains($favoriteUser) && $favoriteUser != $this) {
            $this->favoriteUsers[] = $favoriteUser;
        }

        return $this;
    }

    public function removeFavoriteUser(self $favoriteUser): self
    {
        if ($this->favoriteUsers->contains($favoriteUser)) {
            $this->favoriteUsers->removeElement($favoriteUser);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFans(): Collection
    {
        return $this->fans;
    }

    public function addFan(self $fan): self
    {
        if (!$this->fans->contains($fan) && $fan != $this) {
            $this->fans[] = $fan;
            $fan->addFavoriteUser($this);
        }

        return $this;
    }

    public function removeFan(self $fan): self
    {
        if ($this->fans->contains($fan)) {
            $this->fans->removeElement($fan);
            $fan->removeFavoriteUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Achievement[]
     */
    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    public function addAchievement(Achievement $achievement): self
    {
        if (!$this->achievements->contains($achievement)) {
            $this->achievements[] = $achievement;
            $achievement->addOwner($this);
        }

        return $this;
    }

    public function removeAchievement(Achievement $achievement): self
    {
        if ($this->achievements->contains($achievement)) {
            $this->achievements->removeElement($achievement);
            $achievement->removeOwner($this);
        }

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getCurrentRoles(): Collection
    {
        return $this->currentRoles;
    }

    public function addCurrentRole(Role $currentRole): self
    {
        if (!$this->currentRoles->contains($currentRole)) {
            $this->currentRoles[] = $currentRole;
            $currentRole->addUser($this);
        }

        return $this;
    }

    public function removeCurrentRole(Role $currentRole): self
    {
        if ($this->currentRoles->contains($currentRole)) {
            $this->currentRoles->removeElement($currentRole);
            $currentRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setWriter($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getWriter() === $this) {
                $post->setWriter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Response[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setWriter($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getWriter() === $this) {
                $response->setWriter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Fighter[]
     */
    public function getFighters(): Collection
    {
        return $this->fighters;
    }

    public function addFighter(Fighter $fighter): self
    {
        if (!$this->fighters->contains($fighter)) {
            $this->fighters[] = $fighter;
            $fighter->setOwner($this);
        }

        return $this;
    }

    public function removeFighter(Fighter $fighter): self
    {
        if ($this->fighters->contains($fighter)) {
            $this->fighters->removeElement($fighter);
            // set the owning side to null (unless already changed)
            if ($fighter->getOwner() === $this) {
                $fighter->setOwner(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getTempToken(): ?string
    {
        return $this->tempToken;
    }

    public function setTempToken(?string $tempToken): self
    {
        $this->tempToken = $tempToken;

        return $this;
    }
}

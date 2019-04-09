<?php

namespace App\Entity;

use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FighterRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Fighter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $strength;

    /**
     * @ORM\Column(type="integer")
     */
    private $dexterity;

    /**
     * @ORM\Column(type="integer")
     */
    private $vitality;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $experience;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $defenseWon;

    /**
     * @ORM\Column(type="integer")
     */
    private $defenseLost;

    /**
     * @ORM\Column(type="integer")
     */
    private $attackWon;

    /**
     * @ORM\Column(type="integer")
     */
    private $attackLost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="fighters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Fight", mappedBy="fighter", orphanRemoval=true)
     */
    private $fightsGiven;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Fight", mappedBy="opponent", orphanRemoval=true)
     */
    private $fightsTaken;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Weapon", mappedBy="owners")
     */
    private $weapons;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Armor", mappedBy="owners")
     */
    private $armors;

    public function __construct()
    {
        $this->fightsGiven = new ArrayCollection();
        $this->fightsTaken = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->armors = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {

        if(empty($this->strength)) $this->strength          = 100;
        if(empty($this->dexterity)) $this->dexterity        = 100;
        if(empty($this->vitality)) $this->vitality          = 100;
        if(empty($this->level)) $this->level                = 1;
        if(empty($this->experience)) $this->experience      = 0;
        if(empty($this->defenseWon)) $this->defenseWon      = 0;
        if(empty($this->defenseLost)) $this->defenseLost    = 0;
        if(empty($this->attackWon)) $this->attackWon        = 0;
        if(empty($this->attackLost)) $this->attackLost      = 0;
        if(empty($this->createdAt)) {
            $this->createdAt        = new \DateTime();
            $this->createdAt->setTimezone(new DateTimeZone('Europe/Brussels'));
        }
    }

    public function getExperienceNeeded() {

        return $this->level * 100;
    }

    public function getTotalWin() {

        return $this->defenseWon + $this->attackWon;
    }

    public function getTotalLoose() {

        return $this->defenseLost + $this->attackLost;
    }

    public function getOwnerFullName() {

        return $this->owner->getFullName();
    }

    public function getOwnerAvatar() {

        return $this->owner->getImage();
    }

    public function getFights() {

        return array_merge($this->fightsGiven->toArray(), $this->fightsTaken->toArray());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getDexterity(): ?int
    {
        return $this->dexterity;
    }

    public function setDexterity(int $dexterity): self
    {
        $this->dexterity = $dexterity;

        return $this;
    }

    public function getVitality(): ?int
    {
        return $this->vitality;
    }

    public function setVitality(int $vitality): self
    {
        $this->vitality = $vitality;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDefenseWon(): ?int
    {
        return $this->defenseWon;
    }

    public function setDefenseWon(int $defenseWon): self
    {
        $this->defenseWon = $defenseWon;

        return $this;
    }

    public function getDefenseLost(): ?int
    {
        return $this->defenseLost;
    }

    public function setDefenseLost(int $defenseLost): self
    {
        $this->defenseLost = $defenseLost;

        return $this;
    }

    public function getAttackWon(): ?int
    {
        return $this->attackWon;
    }

    public function setAttackWon(int $attackWon): self
    {
        $this->attackWon = $attackWon;

        return $this;
    }

    public function getAttackLost(): ?int
    {
        return $this->attackLost;
    }

    public function setAttackLost(int $attackLost): self
    {
        $this->attackLost = $attackLost;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Fight[]
     */
    public function getFightsGiven(): Collection
    {
        return $this->fightsGiven;
    }

    public function addFightsGiven(Fight $fightsGiven): self
    {
        if (!$this->fightsGiven->contains($fightsGiven)) {
            $this->fightsGiven[] = $fightsGiven;
            $fightsGiven->setFighter($this);
        }

        return $this;
    }

    public function removeFightsGiven(Fight $fightsGiven): self
    {
        if ($this->fightsGiven->contains($fightsGiven)) {
            $this->fightsGiven->removeElement($fightsGiven);
            // set the owning side to null (unless already changed)
            if ($fightsGiven->getFighter() === $this) {
                $fightsGiven->setFighter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Fight[]
     */
    public function getFightsTaken(): Collection
    {
        return $this->fightsTaken;
    }

    public function addFightsTaken(Fight $fightsTaken): self
    {
        if (!$this->fightsTaken->contains($fightsTaken)) {
            $this->fightsTaken[] = $fightsTaken;
            $fightsTaken->setOpponent($this);
        }

        return $this;
    }

    public function removeFightsTaken(Fight $fightsTaken): self
    {
        if ($this->fightsTaken->contains($fightsTaken)) {
            $this->fightsTaken->removeElement($fightsTaken);
            // set the owning side to null (unless already changed)
            if ($fightsTaken->getOpponent() === $this) {
                $fightsTaken->setOpponent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Weapon[]
     */
    public function getWeapons(): Collection
    {
        return $this->weapons;
    }

    public function addWeapon(Weapon $weapon): self
    {
        if (!$this->weapons->contains($weapon)) {
            $this->weapons[] = $weapon;
            $weapon->addOwner($this);
        }

        return $this;
    }

    public function removeWeapon(Weapon $weapon): self
    {
        if ($this->weapons->contains($weapon)) {
            $this->weapons->removeElement($weapon);
            $weapon->removeOwner($this);
        }

        return $this;
    }

    /**
     * @return Collection|Armor[]
     */
    public function getArmors(): Collection
    {
        return $this->armors;
    }

    public function addArmor(Armor $armor): self
    {
        if (!$this->armors->contains($armor)) {
            $this->armors[] = $armor;
            $armor->addOwner($this);
        }

        return $this;
    }

    public function removeArmor(Armor $armor): self
    {
        if ($this->armors->contains($armor)) {
            $this->armors->removeElement($armor);
            $armor->removeOwner($this);
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FightRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Fight
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fighter", inversedBy="fightsGiven")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fighter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fighter", inversedBy="fightsTaken")
     * @ORM\JoinColumn(nullable=false)
     */
    private $opponent;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isWon;

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {

        if(empty($this->createdAt)) {

            $this->createdAt = new \DateTime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFighter(): ?Fighter
    {
        return $this->fighter;
    }

    public function setFighter(?Fighter $fighter): self
    {
        $this->fighter = $fighter;

        return $this;
    }

    public function getOpponent(): ?Fighter
    {
        return $this->opponent;
    }

    public function setOpponent(?Fighter $opponent): self
    {
        $this->opponent = $opponent;

        return $this;
    }

    public function getIsWon(): ?bool
    {
        return $this->isWon;
    }

    public function setIsWon(bool $isWon): self
    {
        $this->isWon = $isWon;

        return $this;
    }
}

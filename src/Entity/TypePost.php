<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypePostRepository")
 */
class TypePost
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
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="type", orphanRemoval=true)
     */
    private $relatedPosts;

    public function __construct()
    {
        $this->relatedPosts = new ArrayCollection();
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

    /**
     * @return Collection|Post[]
     */
    public function getRelatedPosts(): Collection
    {
        return $this->relatedPosts;
    }

    public function addRelatedPost(Post $relatedPost): self
    {
        if (!$this->relatedPosts->contains($relatedPost)) {
            $this->relatedPosts[] = $relatedPost;
            $relatedPost->setType($this);
        }

        return $this;
    }

    public function removeRelatedPost(Post $relatedPost): self
    {
        if ($this->relatedPosts->contains($relatedPost)) {
            $this->relatedPosts->removeElement($relatedPost);
            // set the owning side to null (unless already changed)
            if ($relatedPost->getType() === $this) {
                $relatedPost->setType(null);
            }
        }

        return $this;
    }
}

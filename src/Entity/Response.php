<?php

namespace App\Entity;

use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResponseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Response
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
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Your message must not be blank")
     * @Assert\Length(min=20, minMessage="Your message must be longer")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="responses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $writer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="responses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relatedPost;

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {

        if(empty($this->createdAt)) {

            $this->createdAt = new \DateTime();
            $this->createdAt->setTimezone(new DateTimeZone('Europe/Brussels'));
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getWriter(): ?User
    {
        return $this->writer;
    }

    public function setWriter(?User $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    public function getRelatedPost(): ?Post
    {
        return $this->relatedPost;
    }

    public function setRelatedPost(?Post $relatedPost): self
    {
        $this->relatedPost = $relatedPost;

        return $this;
    }
}

<?php

namespace App\Entity;

use DateTimeZone;
use App\Entity\Response;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="You have to pick a subject title")
     * @Assert\Length(min=8, minMessage="Your subject title must be longer")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $writer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Response", mappedBy="relatedPost", orphanRemoval=true)
     */
    private $responses;

    /**
     * @Assert\NotBlank(message="Your message must not be blank")
     * @Assert\Length(min=20, minMessage="Your message must be longer")
     */
    private $firstMessage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePost", inversedBy="relatedPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {

        if(empty($this->createdAt)) {

            $this->createdAt = new \DateTime();
            $this->createdAt->setTimezone(new DateTimeZone('Europe/Brussels'));
        }
    }

    public function getLastMessageTime() {

        $mostRecentMessage = new Response();
        $mostRecentTime = 0;

        foreach($this->responses as $message) {

            $currentTime = \strtotime($message->getCreatedAt()->format('Y-m-d H:i:s'));
            if($currentTime > $mostRecentTime) {

                $mostRecentMessage = $message;
                $mostRecentTime = $currentTime;
            }
        }

        return $mostRecentMessage;
    }

    public function getPostLabel() {

        switch($this->type->getName()) {

            case('Discussion'):
                return 'fa-comments';
            case('Request'):
                return 'fa-check-square';
            case('Technical problems'):
                return 'fa-skull-crossbones';
            default:
                return 'secondary';
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getFirstMessage() {

        return $this->firstMessage;
    }

    public function setFirstMessage(string $message) {

        $this->firstMessage = $message;

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
            $response->setRelatedPost($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getRelatedPost() === $this) {
                $response->setRelatedPost(null);
            }
        }

        return $this;
    }

    public function getType(): ?TypePost
    {
        return $this->type;
    }

    public function setType(?TypePost $type): self
    {
        $this->type = $type;

        return $this;
    }
}

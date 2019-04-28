<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 */
class Note
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
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PatchNote", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patchNote;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPatchNote(): ?PatchNote
    {
        return $this->patchNote;
    }

    public function setPatchNote(?PatchNote $patchNote): self
    {
        $this->patchNote = $patchNote;

        return $this;
    }
}

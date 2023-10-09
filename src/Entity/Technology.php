<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
class Technology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Resume::class, mappedBy: 'technology')]
    private Collection $resumes;

    public function __construct()
    {
        $this->resumes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Resume>
     */
    public function getResumes(): Collection
    {
        return $this->resumes;
    }

    public function addResume(Resume $resume): static
    {
        if (!$this->resumes->contains($resume)) {
            $this->resumes->add($resume);
            $resume->addTechnology($this);
        }

        return $this;
    }

    public function removeResume(Resume $resume): static
    {
        if ($this->resumes->removeElement($resume)) {
            $resume->removeTechnology($this);
        }

        return $this;
    }
}

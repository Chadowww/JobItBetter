<?php

namespace App\Entity;

use App\Repository\JobofferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: JobofferRepository::class)]
#[ORM\Table(name: 'joboffer')]
#[ORM\Index(columns: ['title', 'city', 'description'], flags: ['fulltext'])]
class Joboffer
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'joboffers')]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'joboffers')]
    private ?Job $job = null;

    #[ORM\ManyToOne(inversedBy: 'joboffers')]
    private ?Contract $contract = null;

    #[ORM\ManyToOne(inversedBy: 'joboffers')]
    private ?Salary $salary = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $salaryMin = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $salaryMax = null;


    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favlist')]
    private Collection $usersInterested;

    public function __construct()
    {
        $this->usersInterested = new ArrayCollection();
        $this->candidate = new ArrayCollection();
    }

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'joboffers')]
    private Collection $candidate;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getSalary(): ?Salary
    {
        return $this->salary;
    }

    public function setSalary(?Salary $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getSalaryMin(): ?int
    {
        return $this->salaryMin;
    }

    public function getSalaryMax(): int
    {
        return $this->salaryMax;
    }

    public function setSalaryMin(?int $salaryMin): self
    {
        $this->salaryMin = $salaryMin;

        return $this;
    }

    public function setSalaryMax(?int $salaryMax): self
    {
        $this->salaryMax = $salaryMax;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */

    public function getUsersInterested(): Collection
    {
        return $this->usersInterested;
    }

    public function addUsersInterested(User $usersInterested): static
    {
        if (!$this->usersInterested->contains($usersInterested)) {
            $this->usersInterested->add($usersInterested);
            $usersInterested->addToFavlist($this);
        }

        return $this;
    }

    public function removeUsersInterested(User $usersInterested): static
    {
        if ($this->usersInterested->removeElement($usersInterested)) {
            $usersInterested->removeFromFavlist($this);
        }
        return $this;
    }

    public function getCandidate(): Collection
    {
        return $this->candidate;
    }

    public function addCandidate(User $candidate): static
    {
        if (!$this->candidate->contains($candidate)) {
            $this->candidate->add($candidate);
        }

        return $this;
    }

    public function removeCandidate(User $candidate): static
    {
        $this->candidate->removeElement($candidate);

        return $this;
    }
}

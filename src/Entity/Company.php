<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['siret'], message: 'Un compte existe déjà avec ce numéro de SIRET')]
class Company
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $logo = null;

    #[Vich\UploadableField(mapping: 'logo_file', fileNameProperty: 'logo')]
    private ?File $logoFile = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $siret = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Joboffer::class)]
    private Collection $joboffers;

    #[ORM\OneToOne(inversedBy: 'company', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Search::class)]
    private Collection $searches;

    #[ORM\OneToMany(mappedBy: 'employer', targetEntity: Alert::class, orphanRemoval: true)]
    private Collection $alerts;

    public function __construct()
    {
        $this->joboffers = new ArrayCollection();
        $this->searches = new ArrayCollection();
        $this->alerts = new ArrayCollection();
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function setLogoFile(File $image = null): Company
    {
        $this->logoFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection<int, Joboffer>
     */
    public function getJoboffers(): Collection
    {
        return $this->joboffers;
    }

    public function addJoboffer(Joboffer $joboffer): self
    {
        if (!$this->joboffers->contains($joboffer)) {
            $this->joboffers->add($joboffer);
            $joboffer->setCompany($this);
        }

        return $this;
    }

    public function removeJoboffer(Joboffer $joboffer): self
    {
        if ($this->joboffers->removeElement($joboffer)) {
            // set the owning side to null (unless already changed)
            if ($joboffer->getCompany() === $this) {
                $joboffer->setCompany(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->name;
    }

    /**
     * @return Collection<int, Search>
     */
    public function getSearches(): Collection
    {
        return $this->searches;
    }

    public function addSearches(Search $job): static
    {
        if (!$this->searches->contains($job)) {
            $this->searches->add($job);
            $job->setCompany($this);
        }

        return $this;
    }

    public function removeSearch(Search $job): static
    {
        if ($this->searches->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getCompany() === $this) {
                $job->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Alert>
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): static
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts->add($alert);
            $alert->setEmployer($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): static
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getEmployer() === $this) {
                $alert->setEmployer(null);
            }
        }

        return $this;
    }
}

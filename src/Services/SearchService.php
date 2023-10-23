<?php

namespace App\Services;

use AllowDynamicProperties;
use App\Entity\Joboffer;
use App\Entity\Search;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SearchService
{
    public Search $search;
    private EntityManagerInterface $manager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->search = new Search();
        $this->manager = $entityManager;
    }

    public function addSearch(Joboffer $data, User $user): static
    {
        $this->search->setUser($user);
        $this->search->setJob($data->getJob());
        $this->search->setCity($data->getCity());
        $this->search->setContract($data->getContract());
        $this->search->setCompany($data->getCompany());
        $this->manager->persist($this->search);
        $this->manager->flush();

        return $this;
    }

    public function removeSearch(Search $search): static
    {
        $this->manager->remove($search);

        return $this;
    }
    public function hasSearch(Search $search, UserInterface $user): bool
    {
        return $search->getUser() === $user;
    }
}

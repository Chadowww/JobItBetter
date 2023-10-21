<?php

namespace App\Services;

use App\Entity\Alert;
use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AlertService
{
    private Alert $alert;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->alert = new Alert();
        $this->entityManager = $entityManager;
    }

    public function addAlert(UserInterface $user, User $resumeUser): void
    {
        $this->alert->setApplicant($resumeUser);
        $this->alert->setEmployer($user->getCompany());
        $this->alert->setMessage('a  vu votre candidature.');
        $this->alert->setState(true);
        $this->entityManager->persist($this->alert);
        $this->entityManager->flush();
    }

    public function addAlertCompany(UserInterface $user, Company $company): void
    {
        $this->alert->setApplicant($user);
        $this->alert->setEmployer($company);
        $this->alert->setMessage('a un nouveau candidat?');
        $this->alert->setState(true);
        $this->entityManager->persist($this->alert);
        $this->entityManager->flush();
    }
}

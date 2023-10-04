<?php

namespace App\Services;

use App\Entity\Alert;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class AlertService
{
    private Alert $alert;
    public function __construct()
    {
        $this->alert = new Alert();
    }

    public function addAlert(UserInterface $user, User $resumeUser): void
    {
        $this->alert->setApplicant($resumeUser);
        $this->alert->setEmployer($user->getCompany());
        $this->alert->setMessage('Votre candidture  été vu');
        $this->alert->setState(true);
    }
}

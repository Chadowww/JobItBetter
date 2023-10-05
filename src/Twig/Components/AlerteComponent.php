<?php

namespace App\Twig\Components;

use App\Entity\Alert;
use App\Entity\User;
use App\Repository\AlertRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class AlerteComponent
{
    use DefaultActionTrait;

    private AlertRepository $alert;

    public function __construct(AlertRepository $repository)
    {
        $this->alert = $repository;
    }

    public function getAlert(User $applicant): array
    {

        return $this->alert->findBy(['applicant' => $applicant]);
    }

    public function readedAlert(Alert $alert): bool
    {
        $this->alert->readed($alert);
        return true;
    }
}

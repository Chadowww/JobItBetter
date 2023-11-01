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

    public function getAlert(int $applicant): array
    {

        $alertes =  $this->alert
                    ->createQueryBuilder('a')
                    ->where('a.applicant = :applicant')
                    ->setParameter('applicant', $applicant)
                    ->orderBy('a.id', 'DESC')
                    ->getQuery()
                    ->getResult();
        $alertesJson = [];
        foreach ($alertes as $alerte) {
            $alertesJson[] = [
                'id' => $alerte->getId(),
                'applicant' => $alerte->getApplicant()->getFirstname(),
                'company' => $alerte->getEmployer()->getName(),
                'content' => $alerte->getMessage(),
                'readed' => $alerte->getState(),
            ];
        }
        return $alertesJson;
    }

    public function readedAlert(Alert $alert): bool
    {
        $this->alert->readed($alert);
        return true;
    }
}

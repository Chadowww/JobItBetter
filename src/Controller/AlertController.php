<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlertController extends AbstractController
{
    private Alert $alert;
    private UserRepository $userRepository;

    public function __construct(Alert $alert, UserRepository $userRepository)
    {
        $this->alert = $alert;
        $this->userRepository = $userRepository;
    }

    #[Route('/{id}/alert/', name: 'app_alerte_edit')]
    public function readAlert(): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($user->getAlert($this->alert) === true) {
            $user->readAlert($this->alert);
        }
        $this->userRepository->save($user, true);

        return $this->json([
            'success' => $user->getAlerts()->contains($this->alert),
            'state' => $this->alert->getState(),
            'id' => $this->alert->getId(),
        ]);
    }
    #[Route('/{id}/alert/delete', name: 'app_alerte_delete')]
    public function deleteAlert(): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $alertId = null;

        if ($user->getAlert($this->alert) === true) {
            $alertId = $this->alert->getId();
            $user->removeAlert($this->alert);
        }
        $this->userRepository->save($user, true);

        return $this->json([
            'success' => !$user->getAlerts()->contains($this->alert),
            'id' => $alertId,
        ]);
    }
}

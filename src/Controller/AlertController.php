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
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/{id}/alert/', name: 'app_alerte_edit')]
    public function readAlert(Alert $alert): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($user->getAlert($alert) === true) {
            $user->readAlert($alert);
        }
        $this->userRepository->save($user, true);

        return $this->json([
            'success' => $user->getAlerts()->contains($alert),
            'state' => $alert->getState(),
            'id' => $alert->getId(),
        ]);
    }
    #[Route('/{id}/alert/delete', name: 'app_alerte_delete')]
    public function deleteAlert(Alert $alert): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $alertId = null;

        if ($user->getAlert($alert) === true) {
            $alertId = $alert->getId();
            $user->removeAlert($alert);
        }
        $this->userRepository->save($user, true);

        return $this->json([
            'success' => !$user->getAlerts()->contains($alert),
            'id' => $alertId,
        ]);
    }
}

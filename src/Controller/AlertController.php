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
    #[Route('/{id}/alert/', name: 'app_alerte_edit')]
    public function readAlert(Alert $alert, UserRepository $userRepository): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($user->getAlert($alert) === true) {
            $user->readAlert($alert);
        }
        $userRepository->save($user, true);

        return $this->json([
            'success' => $user->getAlerts()->contains($alert),
            'state' => $alert->getState(),
        ]);
    }
}

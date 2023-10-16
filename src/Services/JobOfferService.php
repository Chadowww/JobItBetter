<?php

namespace App\Services;

use App\Entity\Joboffer;
use App\Repository\JobofferRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\{File\File, Request};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Security\Core\User\UserInterface;

class JobOfferService extends AbstractController
{
    private JobofferRepository $jobofferRepository;
    private MailerInterface $mailer;
    public function __construct(JobofferRepository $jobofferRepository, MailerInterface $mailer)
    {
        $this->jobofferRepository = $jobofferRepository;
        $this->mailer = $mailer;
    }


    public function createNewJobOffer(Joboffer $joboffer): Joboffer
    {
        $this->jobofferRepository->save($joboffer, true);

        return $joboffer;
    }

    public function newCadidateMailer(UserInterface $user, Joboffer $joboffer, Request $request): bool
    {
        $message    = $request->get('message');
        $resume = $request->get('resume');

        if ($resume) {
            $attachment = new File(
                $this->getParameter('kernel.project_dir') . '/public/uploads/resume/' . $resume
            );
        } else {
            $attachment = null;
        }

        $email = (new TemplatedEmail())
            ->from($_ENV['MAIL_ADMIN'])
            ->to($joboffer->getCompany()->getUser()->getEmail())
            ->subject('Nouvelle candidature');
        if ($attachment) {
            $email->addPart(new DataPart(fopen($attachment, 'r')));
        }
            $email->html($this->renderView('joboffer/jobOfferEmail.html.twig', [
                'joboffer' => $joboffer,
                'user'     => $user,
                'message'  => $message,
            ]));

//    todo: 'add a try catch here ? '
        if (!$this->mailer->send($email)) {
            return false;
        }

        return true;
    }
}

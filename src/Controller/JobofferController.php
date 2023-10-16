<?php

namespace App\Controller;

use App\Data\FilterData;
use App\Services\NewJobOfferService;
use App\Entity\{Joboffer};
use App\Form\{JobofferApplyType, JobofferFilterType, JobofferType};
use App\Repository\{JobofferRepository, ResumeRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{File\File, Request,Response};
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/joboffer')]
class JobofferController extends AbstractController
{
    private JobofferRepository $jobofferRepository;
    private ResumeRepository $resumeRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $manager;
    private MailerInterface $mailer;
    private NewJobOfferService $newJobOfferService;

    public function __construct(
        JobofferRepository $jobofferRepository,
        ResumeRepository $resumeRepository,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        MailerInterface $mailer,
        NewJobOfferService $newJobOfferService
    ) {
        $this->jobofferRepository = $jobofferRepository;
        $this->resumeRepository = $resumeRepository;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->mailer = $mailer;
        $this->newJobOfferService = $newJobOfferService;
    }

    #[Route('/', name: 'app_joboffer_index', methods: ['GET'])]
    public function index(): Response
    {
        $data = new FilterData();
        $joboffers = $this->jobofferRepository->findByFilter($data);
        [$min, $max] = $this->jobofferRepository->findMinMaxSalary($data);

        return $this->render('joboffer/companyfilter.html.twig', [
            'joboffers' => $joboffers,
            'min' => $min,
            'max' => $max,
            'form' => $this->createForm(JobofferFilterType::class, $data)->createView(),
            'lastResumes' => $this->resumeRepository->lastResumes(),

        ]);
    }

    #[isGranted('ROLE_COMPANY')]
    #[Route('/new', name: 'app_joboffer_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $joboffer = new Joboffer();
        $form = $this->createForm(JobofferType::class, $joboffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $this->newJobOfferService->createNewJobOffer($form->getData())->getId();

            return $this->redirectToRoute('app_joboffer_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('joboffer/new.html.twig', [
            'joboffer' => $joboffer,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_joboffer_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Joboffer $joboffer): Response
    {
        $user = $this->getUser();
        $attachment = null;
        $message = null;

        $form = null;
        if ($user !== null) {
            $form = $this->createForm(JobofferApplyType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $candidate = $form->getData();
                $candidate->addJobOffer($joboffer);
                $this->manager->persist($candidate);
                $this->manager->flush();
                $message    = $request->get('message');
                $resume = $request->get('resume');
                $attachment = new File(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/resume/' . $resume
                );
                $email = (new TemplatedEmail())
                    ->from($_ENV['MAIL_ADMIN'])
                    ->to($joboffer->getCompany()->getUser()->getEmail())
                    ->subject('Nouvelle candidature')
                    ->addPart(new DataPart(fopen($attachment, 'r')))
                    ->html($this->renderView('joboffer/jobOfferEmail.html.twig', [
                        'joboffer' => $joboffer,
                        'user'     => $user,
                        'message'  => $message,
                    ]));


                $this->mailer->send($email);

                $this->addFlash('success', 'Votre candidature a bien été envoyée !');

                return $this->redirectToRoute('app_joboffer_show', [
                    'id' => $joboffer->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('joboffer/show.html.twig', [
            'joboffer' => $joboffer,
            'form' => $form,
        ]);
    }

    #[isgranted('ROLE_COMPANY')]
    #[Route('/{id}/edit', name: 'app_joboffer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Joboffer $joboffer): Response
    {
        if ($this->getUser() !== $joboffer->getCompany()->getUser()) {
            $this->addFlash('danger', 'Seule l\'entreprise qui est propriétaire de l\'offre peut la modifier!');
            return $this->redirectToRoute(
                'app_joboffer_show',
                ['id' => $joboffer->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        $form = $this->createForm(JobofferType::class, $joboffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->jobofferRepository->save($joboffer, true);

            $id = $joboffer->getId();
            return $this->redirectToRoute('app_joboffer_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('joboffer/edit.html.twig', [
            'joboffer' => $joboffer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_joboffer_delete', methods: ['POST'])]
    public function delete(Request $request, Joboffer $joboffer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $joboffer->getId(), $request->request->get('_token'))) {
            $this->jobofferRepository->remove($joboffer, true);
        }
        $id = $joboffer->getCompany()->getId();
        return $this->redirectToRoute(
            'app_company_offers',
            ['id' => $id],
            Response::HTTP_SEE_OTHER
        );
    }

    #[isGranted('ROLE_CANDIDATE')]
    #[Route('/{id}/favlist', name: 'app_joboffer_favlist', methods: ['GET', 'POST'])]
    public function addToFavlist(Joboffer $joboffer): Response
    {
        /** @var  \App\Entity\User $user */
        $user = $this->getUser();

        if ($user->isInFavlist($joboffer)) {
            $user->removeFromFavlist($joboffer);
        } else {
            $user->addToFavlist($joboffer);
        }

        $this->userRepository->save($user, true);


        return $this->json([
            'isInFavlist' => $user->isInFavlist($joboffer)
        ]);
    }

    #[Route('/company/{id}', name: 'app_joboffer_company_filter', methods: ['GET'])]
    public function getJoboffersByCompany(int $id, Request $request): Response
    {
        $data = new FilterData();
        $data->company = $id;
        $form = $this->createForm(JobofferFilterType::class, $data);
        $form->handleRequest($request);

            $joboffer = $this->jobofferRepository->findByFilter($data);
            [$min, $max] = $this->jobofferRepository->findMinMaxSalary($data);


        return $this->render('joboffer/companyfilter.html.twig', [
            'joboffers' => $joboffer,
            'lastResumes' => $this->resumeRepository->lastResumes(),
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max,
        ]);
    }
}

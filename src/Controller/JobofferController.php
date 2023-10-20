<?php

namespace App\Controller;

use App\Data\FilterData;
use App\Services\AlertService;
use App\Services\JobOfferService;
use App\Services\FilterService;
use App\Entity\{Joboffer, User};
use App\Form\{JobofferApplyType, JobofferFilterType, JobofferType};
use App\Repository\{JobofferRepository, ResumeRepository};
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{ Request,Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/joboffer')]
class JobofferController extends AbstractController
{
    private JobofferRepository $jobofferRepository;
    private ResumeRepository $resumeRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $manager;
    private JobOfferService $jobOfferService;
    private FilterService $filter;
    private AlertService $alertService;

    public function __construct(
        JobofferRepository $jobofferRepository,
        ResumeRepository $resumeRepository,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        JobOfferService $newJobOfferService,
        FilterService $filter,
        AlertService $alertService
    ) {
        $this->jobofferRepository = $jobofferRepository;
        $this->resumeRepository = $resumeRepository;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->jobOfferService = $newJobOfferService;
        $this->filter = $filter;
        $this->alertService = $alertService;
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
            $id = $this->jobOfferService->createNewJobOffer($form->getData())->getId();

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

        $form = null;
        if ($user !== null) {
            $form = $this->createForm(JobofferApplyType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->manager->persist(
                    $form->getData()->addJobOffer($joboffer)
                );
                $this->manager->flush();

                $this->alertService->addAlertCompany($joboffer->getCompany()->getUser(), $joboffer->getCompany());
                $this->jobOfferService->newCadidateMailer($user, $joboffer, $request);
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


    #[isgranted('ROLE_COMPANY')]
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
    public function addToFavlist(Joboffer $joboffer, User $user): Response
    {

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


        return $this->render('joboffer/companyfilter.html.twig', [
            'joboffers' => $joboffer,
            'lastResumes' => $this->resumeRepository->lastResumes(),
            'form' => $this->filter->filterForm($data),
            'min' => $this->filter->getMinMaxSalary($data)[0],
            'max' => $this->filter->getMinMaxSalary($data)[1],
        ]);
    }
}

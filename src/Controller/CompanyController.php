<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/company')]
class CompanyController extends AbstractController
{
    private CompanyRepository $companyRepository;
    private UserRepository $userRepository;
    private Company $company;
    private User $user;

    public function __construct(
        CompanyRepository $companyRepository,
        UserRepository $userRepository,
        Company $company,
        User $user
    ) {
        $this->companyRepository = $companyRepository;
        $this->userRepository = $userRepository;
        $this->company = $company;
        $this->user = $user;
    }
    #[Route('/', name: 'app_company_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('company/index.html.twig', [
            'companies' => $this->companyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->companyRepository->save($company, true);

            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(): Response
    {
        $date = new DateTime();
        $dateString = $date->format('Y-m-d H:i:s');

        return $this->render('company/show.html.twig', [
            'company' => $this->company,
            'currentDateTime' => $dateString
        ]);
    }

    #[Route('/{id}/candidate', name: 'app_company_show_candidate', methods: ['GET'])]
    public function showCandidate(): Response
    {
        $date = new DateTime();
        $dateString = $date->format('Y-m-d H:i:s');

        return $this->render('company/showCandidate.html.twig', [
            'company' => $this->company,
            'currentDateTime' => $dateString
        ]);
    }

    #[Route('/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $form = $this->createForm(CompanyType::class, $this->company);
        $form->add('user', UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->companyRepository->save($this->company, true);
            $this->userRepository->save($this->user, true);

            return $this->redirectToRoute('app_company_show', [
                'id' => $this->company->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/edit.html.twig', [
            'company' => $this->company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $this->company->getId(), $request->request->get('_token'))) {
            $this->companyRepository->remove($this->company, true);
        }

        return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/offers', name: 'app_company_offers', methods: ['GET'])]
    public function showCompanyOffers(): Response
    {
        $offers = $this->company->getJoboffers();

        return $this->render('company/offers.html.twig', [
            'offers' => $offers,
            'company' => $this->company
        ]);
    }
}

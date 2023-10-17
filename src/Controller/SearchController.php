<?php

namespace App\Controller;

use App\Data\FilterData;
use App\Entity\Search;
use App\Entity\User;
use App\Form\SearchType;
use App\Services\FilterService;
use App\Repository\{JobofferRepository, ResumeRepository, SearchRepository};
use App\Services\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search')]
class SearchController extends AbstractController
{
    private SearchService $searchService;
    private FilterService $filterService;
    private JobofferRepository $jobofferRepository;
    private ResumeRepository $resumeRepository;
    private SearchRepository $searchRepository;
    private EntityManagerInterface $manager;

    public function __construct(
        SearchService $searchService,
        FilterService $filterService,
        JobofferRepository $jobofferRepository,
        ResumeRepository $resumeRepository,
        SearchRepository $searchRepository,
        EntityManagerInterface $manager
    ) {
        $this->searchService = $searchService;
        $this->filterService = $filterService;
        $this->jobofferRepository = $jobofferRepository;
        $this->resumeRepository = $resumeRepository;
        $this->searchRepository = $searchRepository;
        $this->manager = $manager;
    }

    #[Route('/', name: 'app_search_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'searches' => $this->searchRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_search_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->searchRepository->save($search, true);

            return $this->redirectToRoute('app_search_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('search/new.html.twig', [
            'search' => $search,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_search_show', methods: ['GET'])]
    public function show(Search $search): Response
    {
        return $this->render('search/show.html.twig', [
            'search' => $search,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_search_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Search $search): Response
    {
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->searchRepository->save($search, true);

            return $this->redirectToRoute('app_search_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('search/edit.html.twig', [
            'search' => $search,
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name: 'app_search_delete', methods: ['GET','POST'])]
    public function delete(Search $search, User $user): Response
    {
        if ($this->searchService->hasSearch($search, $user)) {
            $this->searchService->removeSearch($search);
            $this->manager->flush();
        }
        $this->addFlash('success', 'Votre recherche a bien été supprimée');
        return $this->redirectToRoute('app_user_search', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/ma-recherche/{id}', name: 'app_joboffer_Mysearch', methods: ['GET', 'POST'])]
    public function mySearch(Request $request): Response
    {
        $searchId = $request->request->get('searchId');
        $search = $this->searchRepository->find($searchId);
        $data = new FilterData();

        if (!$search) {
            throw $this->createNotFoundException('La recherche n\'existe pas');
        }

        $result =  $this->jobofferRepository->findByMySearch($search);

        return $this->render('joboffer/companyfilter.html.twig', [
            'joboffers' => $result,
            'form' => $this->filterService->filterForm($data),
            'min' => $this->filterService->getMinMaxSalary($data)[0],
            'max' => $this->filterService->getMinMaxSalary($data)[1],
            'lastResumes' => $this->resumeRepository->lastResumes(),
        ]);
    }
}

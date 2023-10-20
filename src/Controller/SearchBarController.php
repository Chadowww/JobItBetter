<?php

namespace App\Controller;

use App\Data\FilterData;
use App\Form\JobofferFilterType;
use App\Repository\{JobofferRepository, ResumeRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class SearchBarController extends AbstractController
{
    private JobofferRepository $jobofferRepository;
    private ResumeRepository $resumeRepository;
    public function __construct(
        JobofferRepository $jobofferRepository,
        ResumeRepository $resumeRepository,
    ) {
        $this->jobofferRepository = $jobofferRepository;
        $this->resumeRepository = $resumeRepository;
    }

    #[Route('/rechercher', name: 'app_joboffer_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $data = new FilterData();
        /** @var array<string, string> $queryParams */
        $queryParams = (array) $request->query->all();
        if (isset($request->query->all()['what'])) {
            $data->q = implode(' ', $queryParams);
        }

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

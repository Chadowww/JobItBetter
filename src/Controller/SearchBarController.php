<?php

namespace App\Controller;

use App\Data\FilterData;
use App\Form\JobofferFilterType;
use App\Repository\{JobofferRepository, ResumeRepository};
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class SearchBarController extends AbstractController
{
    #[Route('/search', name: 'app_joboffer_search', methods: ['GET'])]
    public function search(
        JobofferRepository $jobofferRepository,
        ResumeRepository $resumeRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {

        $data = new FilterData();
        $form = $this->createForm(JobofferFilterType::class, $data);
        $form->handleRequest($request);

        $joboffer = $jobofferRepository->findByFilter($data);
        [$min, $max] = $jobofferRepository->findMinMaxSalary($data);
        $joboffer = $paginator->paginate(
            $joboffer,
            1,
            10
        );

        return $this->render('joboffer/companyfilter.html.twig', [
            'joboffers' => $joboffer,
            'lastResumes' => $resumeRepository->lastResumes(),
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max,
        ]);
    }
}

<?php

namespace App\Services;

use App\Data\FilterData;
use App\Form\JobofferFilterType;
use App\Repository\JobofferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;

class FilterService extends AbstractController
{
    private JobofferRepository $jobofferRepository;

    public function __construct(JobofferRepository $jobofferRepository)
    {
        $this->jobofferRepository = $jobofferRepository;
    }

    public function filterForm(FilterData $data): FormView
    {
        return $this->createForm(JobofferFilterType::class, $data)->createView();
    }

    public function getMinMaxSalary(FilterData $data): array
    {
        return $this->jobofferRepository->findMinMaxSalary($data);
    }
}

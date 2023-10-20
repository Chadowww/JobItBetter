<?php

namespace App\Twig\Components;

use App\Repository\JobofferRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class JobOffersComponent
{
    use DefaultActionTrait;

    private JobofferRepository $jobofferRepository;
    public function __construct(JobofferRepository $jobofferRepository)
    {
        $this->jobofferRepository = $jobofferRepository;
    }

    public function getJobOffers(): array
    {
        return $this->jobofferRepository->findBy([], ['id' => 'DESC'], 5);
    }
}

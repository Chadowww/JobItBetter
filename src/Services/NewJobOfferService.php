<?php

namespace App\Services;

use App\Entity\Joboffer;
use App\Repository\JobofferRepository;

class NewJobOfferService
{
    private JobofferRepository $jobofferRepository;
    public function __construct(JobofferRepository $jobofferRepository,)
    {
        $this->jobofferRepository = $jobofferRepository;
    }

    public function createNewJobOffer(Joboffer $joboffer): Joboffer
    {
        $this->jobofferRepository->save($joboffer, true);

        return $joboffer;
    }
}

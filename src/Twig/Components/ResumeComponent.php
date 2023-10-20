<?php

namespace App\Twig\Components;

use App\Repository\ResumeRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class ResumeComponent
{
    use DefaultActionTrait;

    private ResumeRepository $resumeRepository;

    public function __construct(ResumeRepository $resumeRepository)
    {
        $this->resumeRepository = $resumeRepository;
    }

    public function getResume(): array
    {
        return $this->resumeRepository->lastResumes();
    }
}

<?php

namespace App\Services;

use App\Entity\Resume;
use App\Repository\ResumeRepository;
use App\Repository\TechnologyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CvthequeService extends AbstractController
{
    private TechnologyRepository $technologyRepository;
    private ResumeRepository $resumeRepository;
    public function __construct(TechnologyRepository $technologyRepository, ResumeRepository $resumeRepository)
    {
        $this->technologyRepository = $technologyRepository;
        $this->resumeRepository = $resumeRepository;
    }

    public function addResume(Resume $resume): void
    {
        $pdfText = shell_exec(
            'pdftotext ' .
            $this->getParameter('kernel.project_dir') . '/public/uploads/resume/' . $resume->getPath() . ' -'
        );
        $technologies = $this->technologyRepository->findAll();
        foreach ($technologies as $technology) {
            if (str_contains($pdfText, $technology->getName())) {
                $resume->addTechnology($technology);
                $this->resumeRepository->save($resume, true);
            }
        }
    }
}

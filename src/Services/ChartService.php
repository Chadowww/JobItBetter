<?php

namespace App\Services;

use App\Repository\CompanyRepository;
use App\Repository\JobofferRepository;
use App\Repository\JobRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{
    private const CLASS_AGE = ['18-20', '21-30', '31-40', '41-50', '51-60', '61-65',];
    private JobofferRepository $jobofferRepository;
    private JobRepository $jobRepository;
    private TechnologyRepository $technologyRepository;
    private UserRepository $userRepository;
    private ChartBuilderInterface $chartBuilder;

    public function __construct(
        JobofferRepository $jobofferRepository,
        JobRepository $jobRepository,
        TechnologyRepository $technologyRepository,
        UserRepository $userRepository,
        ChartBuilderInterface $chartBuilder
    ) {
        $this->jobofferRepository = $jobofferRepository;
        $this->jobRepository = $jobRepository;
        $this->technologyRepository = $technologyRepository;
        $this->userRepository = $userRepository;
        $this->chartBuilder = $chartBuilder;
    }

    public function chartOffers(): Chart
    {
        $chartOffers = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chartOffers->setData([
            'labels' => array_map(fn($jobs) => $jobs->getName(), $this->jobRepository->findAll()),
            'datasets' => [
                [
                    'label' => 'Nombre d\'offres d\'emploi',
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                    ],
                    'data' => array_map(fn($jobs) => $jobs->getJoboffers()->count(), $this->jobRepository->findAll()),
                ],
            ],
        ]);
        $chartOffers->setOptions([
            'maintainAspectRatio' => true,
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'beginAtZero' => true,
                        ],
                    ],
                ],
            ],
        ]);

        return $chartOffers;
    }


    public function chartSalary(): Chart
    {
        $chartSalary = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chartSalary->setData([
            'labels' =>
                array_map(fn($joboffers) =>
                $joboffers['name'], $this->jobofferRepository->getSalaryByJob()),
            'datasets' => [
                [
                    'label' => 'Salaire minimum',
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                    ],
                    'data' =>
                        array_map(fn($joboffers) =>
                            $joboffers['min'], $this->jobofferRepository->getSalaryByJob()),
                ],
                [
                    'label' => 'Salaire maximum',
                    'backgroundColor' => [
                        'rgb(54, 162, 235)',
                    ],
                    'data' =>
                        array_map(fn($joboffers) =>
                            $joboffers['max'], $this->jobofferRepository->getSalaryByJob()),
                ]
            ],
        ]);

        return $chartSalary;
    }


    public function chartUsers(): Chart
    {
        $chartUser = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chartUser->setData([
            'labels' => self::CLASS_AGE,
            'datasets' => [
                [
                    'label' => 'Nombre de candidats',
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                    ],
                    'data' => [
                        $this->userRepository->countByAge(18, 20),
                        $this->userRepository->countByAge(21, 30),
                        $this->userRepository->countByAge(31, 40),
                        $this->userRepository->countByAge(41, 50),
                        $this->userRepository->countByAge(51, 60),
                        $this->userRepository->countByAge(61, 70),
                    ],
                ],
            ],
        ]);
        $chartUser->setOptions([
            'maintainAspectRatio' => true,
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'beginAtZero' => true,
                        ],
                    ],
                ],
            ],
        ]);

        return $chartUser;
    }

    public function chartResumes(): Chart
    {
        $chartResume = $this->chartBuilder->createChart(Chart::TYPE_POLAR_AREA);
        $chartResume->setData([
            'labels' =>
                array_map(fn($technologies) =>
                    $technologies->getName(), $this->technologyRepository->findAll()),
            'datasets' => [
                [
                    'label' => 'Nombre de CV',
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                    ],
                    'data' =>
                        array_map(fn($technologies) =>
                            $technologies->getResumes()->count(), $this->technologyRepository->findAll()),
                ],
            ],
        ]);
        $chartResume->setOptions([
            'maintainAspectRatio' => true,
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'beginAtZero' => true,
                        ],
                    ],
                ],
            ],
            'title' => 'Nombre de CV par technologies',
        ]);

        return $chartResume;
    }
}

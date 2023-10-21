<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Contract;
use App\Entity\Job;
use App\Entity\Joboffer;
use App\Entity\Resume;
use App\Entity\Salary;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\JobofferRepository;
use App\Repository\JobRepository;
use App\Repository\TechnologyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
        private TechnologyRepository $technologyRepository,
        private JobofferRepository $jobofferRepository,
        private JobRepository $jobRepository,
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator

            ->setController(UserCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }
    #[Route('/admin/stats', name: 'app_admin_stats')]
    public function stats(ChartBuilderInterface $chartBuilder): Response
    {
        $technologies = $this->technologyRepository->findAll();
        $joboffers = $this->jobofferRepository->findAll();
        $jobs = $this->jobRepository->findAll();

        $chartResume = $chartBuilder->createChart(Chart::TYPE_POLAR_AREA);
        $chartResume->setData([
            'labels' => array_map(fn($technologies) => $technologies->getName(), $technologies),
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
                    'data' => array_map(fn($technologies) => $technologies->getResumes()->count(), $technologies),
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

        $chartOffers = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartOffers->setData([
            'labels' => array_map(fn($jobs) => $jobs->getName(), $jobs),
            'datasets' => [
                [
                    'label' => 'Nombre d\'offres d\'emploi',
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
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

        $chartSalary = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartSalary->setData([
            'labels' => array_map(fn($joboffers) => $joboffers->getJob()->getName(), $joboffers),
            'datasets' => [
                [
                    'label' => 'Salaire minimum',
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                    ],
                    'data' => array_map(fn($joboffers) => $joboffers->getSalaryMin(), $joboffers),
                ],
                [
                    'label' => 'Salaire maximum',
                    'backgroundColor' => [
                        'rgb(54, 162, 235)',
                    ],
                    'data' => array_map(fn($joboffers) => $joboffers->getSalaryMax(), $joboffers),
                ]
            ],
        ]);

        return $this->render('admin/stats.html.twig', [
            'chartResume' => $chartResume,
            'chartOffers' => $chartOffers,
            'chartSalary' => $chartSalary,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::subMenu('Utilisateurs', 'fa-solid fa-users')->setSubItems([
            MenuItem::linkToCrud('Créer utilisateur', 'fas fa-plus-circle', User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir utilisateurs', 'fas fa-eye', User::class),
        ]);

        yield MenuItem::subMenu('Catégories', 'fa-solid fa-tag')->setSubItems([
            MenuItem::linkToCrud('Créer catégorie', 'fas fa-plus-circle', Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir catégories', 'fas fa-eye', Category::class),
        ]);

        yield MenuItem::subMenu('Entreprises', 'fa-solid fa-building')->setSubItems([
            MenuItem::linkToCrud('Créer entreprise', 'fas fa-plus-circle', Company::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir entreprise', 'fas fa-eye', Company::class),
        ]);

        yield MenuItem::subMenu('Contrats', 'fa-solid fa-file-contract')->setSubItems([
            MenuItem::linkToCrud('Créer contrat', 'fas fa-plus-circle', Contract::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir contrats', 'fas fa-eye', Contract::class),
        ]);

        yield MenuItem::subMenu('Emplois', 'fa-solid fa-microchip')->setSubItems([
            MenuItem::linkToCrud('Créer emploi', 'fas fa-plus-circle', Job::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir emplois', 'fas fa-eye', Job::class),
        ]);

        yield MenuItem::subMenu('Offres d\'emploi', 'fa-solid fa-clipboard')->setSubItems([
            MenuItem::linkToCrud(
                'Créer offre d\'emploi',
                'fas fa-plus-circle',
                Joboffer::class
            )->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir offre d\'emploi', 'fas fa-eye', Joboffer::class),
        ]);

        yield MenuItem::subMenu('CV', 'fa-solid fa-file')->setSubItems([
            MenuItem::linkToCrud('Voir CV', 'fas fa-eye', Resume::class),
        ]);

        yield MenuItem::subMenu('Salaires', 'fa-solid fa-sack-dollar')->setSubItems([
            MenuItem::linkToCrud('Créer salaire', 'fas fa-plus-circle', Salary::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir salaires', 'fas fa-eye', Salary::class),
        ]);
    }
}

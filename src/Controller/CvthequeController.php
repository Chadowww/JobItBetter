<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Form\CvthequeType;
use App\Repository\ResumeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AllowDynamicProperties] class CvthequeController extends AbstractController
{
    public function __construct(ResumeRepository $resumeRepository, PaginatorInterface $paginator,)
    {
        $this->resumeRepository = $resumeRepository;
        $this->paginator = $paginator;
    }
    #[Route('/cvtheque', name: 'app_cvtheque')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CvthequeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resumes = $this->resumeRepository->searchResumes($form->getData());
            $resumes = $this->paginator->paginate(
                $resumes,
                $request->query->getInt('page', 1),
                10
            );
            return $this->render('cvtheque/index.html.twig', [
                'resumes' => $resumes,
                'form' => $form->createView(),
            ]);
        }


        $resumes = $this->resumeRepository->findAll();
        $resumes = $this->paginator->paginate(
            $resumes,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('cvtheque/index.html.twig', [
            'resumes' => $resumes,
            'form' => $form->createView(),
        ]);
    }
}

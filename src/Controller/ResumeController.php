<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Form\ResumeType;
use App\Services\CvthequeService;
use App\Repository\{ResumeRepository, TechnologyRepository, UserRepository};
use App\Services\AlertService;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/resume')]
class ResumeController extends AbstractController
{
    private AlertService $alertService;
    private UserRepository $userRepository;

    public function __construct(AlertService $alertService, UserRepository $userRepository)
    {
        $this->alertService = $alertService;
        $this->userRepository = $userRepository;
    }

    #[isgranted('ROLE_CANDIDATE')]
    #[Route('/', name: 'app_resume_index', methods: ['GET'])]
    public function index(ResumeRepository $resumeRepository): Response
    {
        $resumes = $resumeRepository->findBy(['user' => $this->getUser()]);

        return $this->render('resume/index.html.twig', [
            'resumes' => $resumes,
        ]);
    }

    #[isgranted('ROLE_CANDIDATE')]
    #[Route('/new', name: 'app_resume_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        ResumeRepository $resumeRepository,
        CvthequeService $cvthequeService
    ): Response {
        $resume = new Resume();
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resume->setUser($this->getUser());
            $resumeRepository->save($resume, true);

            $cvthequeService->addResume($resume);

            return $this->redirectToRoute('app_resume_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resume/new.html.twig', [
            'resume' => $resume,
            'form' => $form,
        ]);
    }
    #[isgranted('ROLE_CANDIDATE')]
    #[Route('/{id}', name: 'app_resume_show', methods: ['GET'])]
    public function show(Resume $resume): false|int
    {
        $file = $this->getParameter('kernel.project_dir') . '/public/uploads/resume/' . $resume->getPath();
        header('Content-type: application/pdf');
        return readfile($file);
    }

    #[isgranted('ROLE_CANDIDATE')]
    #[Route('/{id}/edit', name: 'app_resume_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Resume $resume, ResumeRepository $resumeRepository): Response
    {
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resumeRepository->save($resume, true);

            return $this->redirectToRoute('app_resume_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resume/edit.html.twig', [
            'resume' => $resume,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_resume_delete', methods: ['POST'])]
    public function delete(Request $request, Resume $resume, ResumeRepository $resumeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $resume->getId(), $request->request->get('_token'))) {
            $resumeRepository->remove($resume, true);
        }

        return $this->redirectToRoute('app_resume_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws ORMException
     */
    #[isgranted('ROLE_COMPANY')]
    #[Route('/{id}/read', name: 'app_resume_read', methods: ['GET'])]
    public function readResume(Resume $resume): bool|int
    {
        $resumeUser = $this->userRepository->findOneBy(['id' => $resume->getUser()]);
        if ($resumeUser) {
            $this->alertService->addAlert($this->getUser(), $resumeUser);
        } else {
            throw new ORMException('User not found');
        }
        $file = $this->getParameter('kernel.project_dir') . '/public/uploads/resume/' . $resume->getPath();
        header('Content-type: application/pdf');
        return readfile($file);
    }
}

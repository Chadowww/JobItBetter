<?php

namespace App\Controller;

use App\Entity\Joboffer;
use App\Entity\Search;
use App\Form\UserPersonalSearchType;
use App\Repository\JobofferRepository;
use App\Repository\SalaryRepository;
use App\Services\SearchService;
use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $manager;
    private SearchService $searchService;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        SearchService $searchService
    ) {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->searchService = $searchService;
    }

    #[isgranted('ROLE_CANDIDATE')]
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[isgranted('ROLE_CANDIDATE')]
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {


        return $this->render('user/show.html.twig');
    }

    #[isgranted('ROLE_CANDIDATE')]
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($user, true);

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $this->userRepository->remove($user, true);
        }
        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/favoris', name: 'app_user_favlist', methods: ['GET'])]
    public function favList(User $user): Response
    {
        $favlist = $user->getFavlist();

        return $this->render('user/favlist.html.twig', [
            'favlist' => $favlist,

        ]);
    }

    #[Route('/candidature/{id}', name: 'app_user_candidatures', methods: ['GET', 'POST'])]
    public function candidatures(User $user): Response
    {
        $candidatures = $user->getJoboffers();

        return $this->render('user/candidatures.html.twig', [
            'candidatures' => $candidatures,

        ]);
    }

    #[Route('/candidature/delete/{id}', name: 'app_user_candidatures_delete', methods: ['GET', 'POST'])]
    public function candidaturesDelete(Joboffer $joboffer, User $user): Response
    {

        if ($user->isCandidate($joboffer)) {
            $user->removeJoboffer($joboffer);
            $this->manager->flush();
        }
        return $this->redirectToRoute('app_user_candidatures', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/search', name: 'app_user_search', methods: ['GET', 'POST'])]
    public function mySearch(Request $request, User $user): Response
    {
        $joboffer = new Joboffer();
        $form = $this->createForm(UserPersonalSearchType::class, $joboffer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->searchService->addSearch($form->getData(), $user);

            return $this->redirectToRoute('app_user_search', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

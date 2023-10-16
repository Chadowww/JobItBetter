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

#[isgranted('ROLE_CANDIDATE')]
#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {


        return $this->render('user/show.html.twig');
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }
        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
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
    public function candidaturesDelete(Joboffer $joboffer, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if ($user->isCandidate($joboffer)) {
            $user->removeJoboffer($joboffer);
            $manager->flush();
        }
        return $this->redirectToRoute('app_user_candidatures', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/search', name: 'app_user_search', methods: ['GET', 'POST'])]
    public function mySearch(
        Request $request,
        User $user,
        SearchService $searchService,
    ): Response {
        $joboffer = new Joboffer();
        $form = $this->createForm(UserPersonalSearchType::class, $joboffer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchService->addSearch($form->getData(), $user);

            return $this->redirectToRoute('app_user_search', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

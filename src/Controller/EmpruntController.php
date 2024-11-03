<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Emprunt;
use App\Entity\Film;
use App\Form\EmpruntType;
use App\Repository\EmpruntRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/emprunt')]
class EmpruntController extends AbstractController
{
    private array $menuItems;
    public function __construct(UrlGeneratorInterface $router) {
        $this->menuItems = [
            ['href' => $router->generate('app_film_index') ,'title' => 'Films', 'icon' => "fa-solid fa-film"],
            ['href' => $router->generate('app_categorie_index') ,'title' => 'Categories', 'icon' => "fa-solid fa-list"],
            ['href' => $router->generate('app_acteur_index') ,'title' => 'Actors', 'icon' => "fa-solid fa-user"],
            ['href' => $router->generate('app_emprunt_index') ,'title' => 'Emprunts', 'icon' => "fa-solid fa-link"],
            ['href' => $router->generate('app_adherent_index') ,'title' => 'Adherents', 'icon' => "fa-solid fa-users"],
        ];
    }
    #[Route('/', name: 'app_emprunt_index', methods: ['GET'])]
    public function index(EmpruntRepository $empruntRepository): Response
    {
        return $this->render('emprunt/index.html.twig', [
            'emprunts' => $empruntRepository->findAll(),
            'menuItems' => $this->menuItems,
        ]);
    }

    #[Route('/new', name: 'app_emprunt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emprunt = new Emprunt();
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emprunt);
            $entityManager->flush();

            return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emprunt/new.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunt_show', methods: ['GET'])]
    public function show(Emprunt $emprunt): Response
    {
        return $this->render('emprunt/show.html.twig', [
            'emprunt' => $emprunt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emprunt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emprunt $emprunt, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emprunt/edit.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunt_delete', methods: ['POST'])]
    public function delete(Request $request, Emprunt $emprunt, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emprunt->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($emprunt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
    }

    // user part

    #[Route('/{id}/newEmprunt/', name: 'app_EmpruntUser_new', methods: ['GET', 'POST'])]
    public function newEmpruntUser(UserService $userService, EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $userService->getUser();
        $adherent = $user->getAdherent();
        $film = $entityManager->getRepository(Film::class)->find($id);

        $emprunt = new Emprunt();

        $emprunt->setAdherent($adherent);
        $emprunt->setFilm($film);
        $emprunt->setDhEmprunt(new \DateTime());
        $entityManager->persist($emprunt);
        $entityManager->flush();

        $filmCurrentViews = $film->getViews() ? $film->getViews() : 0 ;
        $film->setViews($filmCurrentViews + 1);
        $entityManager->persist($film);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_films_index', [], Response::HTTP_SEE_OTHER);
    
    }

    #[Route('/{id}/retourEmprunt/', name: 'app_EmpruntUser_retour', methods: ['GET', 'POST'])]
public function retourEmpruntUser(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    $emprunt = $entityManager->getRepository(Emprunt::class)->find($id);

    if (!$emprunt) {
        throw $this->createNotFoundException('No emprunt found for id '.$id);
    }

    $emprunt->setDhRetour(new \DateTime());
    $entityManager->persist($emprunt);
    $entityManager->flush();

    // Get the referer URL
    $referer = $request->headers->get('referer');

    // Redirect to the referer URL or a fallback route if referer is not available
    return $referer ? $this->redirect($referer) : $this->redirectToRoute('app_user_films_index');
}


}

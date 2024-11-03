<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\User;
use App\Form\AdherentType;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/adherent')]
class AdherentController extends AbstractController
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
    #[Route('/', name: 'app_adherent_index', methods: ['GET'])]
    public function index(AdherentRepository $adherentRepository): Response
    {
        return $this->render('adherent/index.html.twig', [
            'adherents' => $adherentRepository->findAll(),
            'menuItems' => $this->menuItems,
        ]);
    }

    #[Route('/new', name: 'app_adherent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adherent = new Adherent();
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adherent->setUpdatedAt(new \DateTime());
            $adherent->setCreatedAt(new \DateTime());
            $entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('app_adherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adherent/new.html.twig', [
            'adherent' => $adherent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adherent_show', methods: ['GET'])]
    public function show(Adherent $adherent): Response
    {
        return $this->render('adherent/show.html.twig', [
            'adherent' => $adherent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adherent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adherent $adherent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adherent->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('app_adherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adherent/edit.html.twig', [
            'adherent' => $adherent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adherent_delete', methods: ['POST'])]
    public function delete(Request $request, Adherent $adherent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adherent->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($adherent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adherent_index', [], Response::HTTP_SEE_OTHER);
    }

    // user part

    #[Route('/newAdherentUser/{id}', name: 'app_newAdherentUser_new', methods: ['GET', 'POST'])]
    public function newAdherentUser(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        // Retrieve the User entity by id
        $user = $entityManager->getRepository(User::class)->find($id);

        // Check if the User entity exists
        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }
        $adherent = new Adherent();
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adherent->setUpdatedAt(new \DateTime());
            $adherent->setCreatedAt(new \DateTime());
            $adherent->setUser($user);
            $entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/adherent/new.html.twig', [
            'adherent' => $adherent,
            'form' => $form,
        ]);
    }
}

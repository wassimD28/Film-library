<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Categorie;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/film')]
class FilmController extends AbstractController
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
    #[Route('/', name: 'app_film_index', methods: ['GET'])]
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
            'menuItems' => $this->menuItems,
        ]);
    }

    #[Route('/new', name: 'app_film_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['couverture']->getData();
            if ($file){
                $fileName = uniqid().'.'. $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/images',
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $film->setCouverture($fileName);
            }
            $film->setCreatedAt(new \DateTime());
            $film->setUpdatedAt(new \DateTime());
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('film/new.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_film_show', methods: ['GET'])]
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_film_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Film $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['couverture']->getData();
            if ($file){
                $fileName = uniqid().'.'. $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/images',
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $film->setCouverture($fileName);
            }
            $film->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('film/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_film_delete', methods: ['POST'])]
    public function delete(Request $request, Film $film, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
    }

    
}

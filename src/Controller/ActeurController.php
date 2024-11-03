<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Form\ActeurType;
use App\Repository\ActeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/acteur')]
class ActeurController extends AbstractController
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
    #[Route('/', name: 'app_acteur_index', methods: ['GET'])]
    public function index(ActeurRepository $acteurRepository): Response
    {
        return $this->render('acteur/index.html.twig', [
            'acteurs' => $acteurRepository->findAll(),
            'menuItems' => $this->menuItems,
        ]);
    }

    #[Route('/new', name: 'app_acteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $acteur = new Acteur();
        $form = $this->createForm(ActeurType::class, $acteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
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
                $acteur->setImage($fileName);
            }
            $acteur->setUpdatedAt(new \DateTime());
            $acteur->setCreatedAt(new \DateTime());
            $entityManager->persist($acteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_acteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('acteur/new.html.twig', [
            'acteur' => $acteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_acteur_show', methods: ['GET'])]
    public function show(Acteur $acteur): Response
    {
        return $this->render('acteur/show.html.twig', [
            'acteur' => $acteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_acteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Acteur $acteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActeurType::class, $acteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
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
                $acteur->setImage($fileName);
            }
            $acteur->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('app_acteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('acteur/edit.html.twig', [
            'acteur' => $acteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_acteur_delete', methods: ['POST'])]
    public function delete(Request $request, Acteur $acteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$acteur->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($acteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_acteur_index', [], Response::HTTP_SEE_OTHER);
    }
}

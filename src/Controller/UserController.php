<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Film;
use App\Repository\CategorieRepository;
use App\Repository\FilmRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/index')]
class UserController extends AbstractController
{
    private array $menuItems;
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->menuItems = [
            ['href' => $router->generate('app_user_films_index'), 'title' => 'Home', 'icon' => "fa-solid fa-house"],
            ['href' => $router->generate('app_findNewMovies_index'), 'title' => 'New movies', 'icon' => "fa-solid fa-newspaper"],
            ['href' => $router->generate('app_findTopWatched_index'), 'title' => 'Top watched', 'icon' => "fa-solid fa-eye"],
            ['href' => $router->generate('app_findTopRated_index'), 'title' => 'Top rated', 'icon' => "fa-solid fa-star"],
        ];
    }

    #[Route('/', name: 'app_user_films_index', methods: ['GET'])]
    public function index(FilmRepository $filmRepository,CategorieRepository $categorieRepository): Response
    {

        return $this->render('user/index.html.twig', [
            'films' => $filmRepository->findAll(),
            'menuItems'=> $this->menuItems,
            'categories' => $categorieRepository->findAllCategoriesWithId(),
        ]);
    }
    #[Route('/findTopWatched', name: 'app_findTopWatched_index', methods: ['GET'])]
    public function findTopWatched(FilmRepository $filmRepository,CategorieRepository $categorieRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'films' => $filmRepository->findTopWatched(),
            'menuItems'=> $this->menuItems,
            'categories' => $categorieRepository->findAllCategoriesWithId(),
        ]);
    }
    #[Route('/findTopRated', name: 'app_findTopRated_index', methods: ['GET'])]
    public function findTopRated(FilmRepository $filmRepository,CategorieRepository $categorieRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'films' => $filmRepository->findTopRated(),
            'menuItems'=> $this->menuItems,
            'categories' => $categorieRepository->findAllCategoriesWithId(),
        ]);
    }
    #[Route('/findNewMovies', name: 'app_findNewMovies_index', methods: ['GET'])]
    public function findNewMovies(FilmRepository $filmRepository,CategorieRepository $categorieRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'films' => $filmRepository->findNewMovies(),
            'menuItems'=> $this->menuItems,
            'categories' => $categorieRepository->findAllCategoriesWithId(),
        ]);
    }

    #[Route('/search', name: 'app_findFilms_index', methods: ['GET'])]
    public function searchFilmsByTitle(Request $request, FilmRepository $filmRepository,CategorieRepository $categorieRepository): Response
    {
        $query = $request->query->get('query', '');
        $films = $filmRepository->searchFilmsByTitle($query);

        return $this->render('user/search.html.twig', [
            'films' => $films,
            'menuItems'=> $this->menuItems,
            "query" => $query,
            'categories' => $categorieRepository->findAllCategoriesWithId(),
        ]);
    }
    #[Route('/category', name: 'app_findFilmsByCategory_index', methods: ['GET'])]
    public function searchFilmsByCategory(Request $request, FilmRepository $filmRepository,CategorieRepository $categorieRepository): Response
    {
        $categoryId = $request->query->get('categoryId', '');
        $films = $filmRepository->findFilmsByCategoryId($categoryId);

        return $this->render('user/search.html.twig', [
            'films' => $films,
            'menuItems'=> $this->menuItems,
            "targetedCategory" => $categorieRepository->find($categoryId),
            'categories' => $categorieRepository->findAllCategoriesWithId(),
        ]);
    }


    #[Route('/{id}', name: 'app_user_film_show', methods: ['GET'])]
    public function show(Film $film, UserService $userService, EntityManagerInterface $em): Response
    {
        $user = $userService->getUser();
        $adherent = $user->getAdherent();

        $emprunt = $em->getRepository(Emprunt::class)->findOneBy(
            ['adherent' => $adherent, 'film' => $film],
            ['dhEmprunt' => 'DESC']
        );

        $bought = false;
        $empruntId = null;
        $returned = false;
        if ($emprunt) {
            $bought = true;
            $empruntId = $emprunt->getId();
            if ($emprunt->getDhRetour()) {
                $returned = true;
                $bought = false;
            }
        }

        return $this->render('user/show.html.twig', [
            'film' => $film,
            'bought' => $bought,
            'empruntId' => $empruntId,
            'returned' => $returned
        ]);
    }

}


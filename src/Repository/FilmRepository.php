<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

//    /**
//     * @return Film[] Returns an array of Film objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Film
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function searchFilmsByTitle(string $query): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.titre LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('f.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findNewMovies(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.dateSortie', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTopWatched(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.views', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTopRated(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.rating', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findFilmsByCategoryId(int $categoryId): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.categorie = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('f.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

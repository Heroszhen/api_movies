<?php

namespace App\Repository;

use App\Entity\MovieVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MovieVideo>
 *
 * @method MovieVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieVideo[]    findAll()
 * @method MovieVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieVideo::class);
    }

//    /**
//     * @return MovieVideo[] Returns an array of MovieVideo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MovieVideo
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

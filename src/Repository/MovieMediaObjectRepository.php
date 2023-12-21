<?php

namespace App\Repository;

use App\Entity\MovieMediaObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MovieMediaObject>
 *
 * @method MovieMediaObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieMediaObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieMediaObject[]    findAll()
 * @method MovieMediaObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieMediaObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieMediaObject::class);
    }

//    /**
//     * @return MovieMediaObject[] Returns an array of MovieMediaObject objects
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

//    public function findOneBySomeField($value): ?MovieMediaObject
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

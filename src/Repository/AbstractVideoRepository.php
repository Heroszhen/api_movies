<?php

namespace App\Repository;

use App\Entity\AbstractVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AbstractVideo>
 *
 * @method AbstractVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractVideo[]    findAll()
 * @method AbstractVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbstractVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractVideo::class);
    }

//    /**
//     * @return AbstractVideo[] Returns an array of AbstractVideo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AbstractVideo
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

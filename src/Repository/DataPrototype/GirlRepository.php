<?php

namespace App\Repository\DataPrototype;

use App\Entity\DataPrototype\Girl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Girl>
 *
 * @method Girl|null find($id, $lockMode = null, $lockVersion = null)
 * @method Girl|null findOneBy(array $criteria, array $orderBy = null)
 * @method Girl[]    findAll()
 * @method Girl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GirlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Girl::class);
    }

//    /**
//     * @return Girl[] Returns an array of Girl objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Girl
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

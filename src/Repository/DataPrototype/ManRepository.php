<?php

namespace App\Repository\DataPrototype;

use App\Entity\DataPrototype\Man;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Man>
 *
 * @method Man|null find($id, $lockMode = null, $lockVersion = null)
 * @method Man|null findOneBy(array $criteria, array $orderBy = null)
 * @method Man[]    findAll()
 * @method Man[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Man::class);
    }

//    /**
//     * @return Man[] Returns an array of Man objects
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

//    public function findOneBySomeField($value): ?Man
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

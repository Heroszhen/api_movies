<?php

namespace App\Repository;

use App\Entity\Actress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Actress>
 *
 * @method Actress|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actress|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actress[]    findAll()
 * @method Actress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actress::class);
    }

//    /**
//     * @return Actress[] Returns an array of Actress objects
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

//    public function findOneBySomeField($value): ?Actress
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

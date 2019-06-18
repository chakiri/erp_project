<?php

namespace App\Repository;

use App\Entity\OrdersHasProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrdersHasProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersHasProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersHasProducts[]    findAll()
 * @method OrdersHasProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersHasProductsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrdersHasProducts::class);
    }

    // /**
    //  * @return OrdersHasProducts[] Returns an array of OrdersHasProducts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrdersHasProducts
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

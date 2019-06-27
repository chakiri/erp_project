<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findAllNotDeletedQuery($statusSearch, $timeSearch): Query
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.isDeleted = false')
        ;

        if (isset($statusSearch) && $statusSearch != null){
            $query
                ->andWhere('p.state = :state')
                ->setParameter('state', $statusSearch)
            ;
        }

        if ($timeSearch){
            switch ($timeSearch){
                case '1d' :
                    $time = date("Y-m-d", strtotime("-1 days"));;
                    break;
                case '3d' :
                    $time = date("Y-m-d", strtotime("-3 days"));;
                    break;
                case '1w' :
                    $time = date("Y-m-d", strtotime("-1 week"));;
                    break;
                case '1m' :
                    $time = date("Y-m-d", strtotime("-1 month"));;
                    break;
            }

            $query
                ->andWhere('p.dateOrder >= :time')
                ->setParameter('time', $time)
            ;
        }

        return $query->getQuery();
    }
}

<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

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

    public function findAllNotDeleted($statusSearch = null, $timeSearch = null)
    {
        return $this->findAllNotDeletedQuery($statusSearch, $timeSearch)
            ->getResult()
            ;
    }

    /* Return an array of arrays
    public function findOrdersByNameT(string $query)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT DISTINCT o.* FROM `Order` o, order_item i WHERE o.customer_id IN (
                      SELECT id FROM Customer WHERE name like '%" . $query . "%'
                ) OR i.order_id = o.id AND i.product_id IN (
                      SELECT id FROM Product WHERE name like '%" . $query . "%'
                )"
            ;

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }*/

    // Return an array of Object
    public function findOrdersByName(string $query)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Order::class, 'o');
        $rsm->addFieldResult('o', 'id', 'id');
        $rsm->addFieldResult('o', 'reference', 'reference');
        $rsm->addFieldResult('o', 'date_order', 'dateOrder');
        $rsm->addFieldResult('o', 'state', 'state');
        $rsm->addFieldResult('o', 'is_deleted', 'isDeleted');
        $rsm->addFieldResult('o', 'price', 'price');

        $rsm->addJoinedEntityResult(Customer::class, 'c', 'o', 'customer');
        $rsm->addFieldResult('c', 'customer_id', 'id');

        $sql = "SELECT DISTINCT o.* FROM `Order` o, order_item i WHERE o.customer_id IN (SELECT c.id FROM Customer c WHERE c.name like '%" . $query . "%') OR i.order_id = o.id AND i.product_id IN (SELECT p.id FROM Product p WHERE p.name like '%" . $query . "%')"
        ;

        $stmt = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $stmt->getResult();
    }

    public function countAllOrders()
    {
        $query = $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.isDeleted = 0')
            ->andWhere('o.state = 1')
        ;

        return $query
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function countAllOrdersByMonth($month, $year)
    {
        $query = $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.isDeleted = 0')
            ->andWhere('o.state = 1')
            ->andWhere('MONTH(o.dateOrder) = :month')
            ->andWhere('YEAR(o.dateOrder) = :year')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
        ;

        return $query
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function totalSumEarningsOrders()
    {
        $query = $this->createQueryBuilder('o')
            ->select('SUM(o.price)')
            ->where('o.isDeleted = 0')
            ->andWhere('o.state = 1')
        ;

        return $query
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function totalSumEarningsOrdersByMonth($month, $year)
    {
        $query = $this->createQueryBuilder('o')
            ->select('SUM(o.price)')
            ->where('o.isDeleted = 0')
            ->andWhere('o.state = 1')
            ->andWhere('MONTH(o.dateOrder) = :month')
            ->andWhere('YEAR(o.dateOrder) = :year')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
        ;

        return $query
            ->getQuery()
            ->getSingleResult()
            ;
    }

}

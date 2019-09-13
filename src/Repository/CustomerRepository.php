<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findAllNotDeletedQuery($typeSearch)
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.isDeleted = 0')
            ;

        if (isset($typeSearch) && $typeSearch != null){
            switch ($typeSearch){
                case 'customer' :
                    $typeSearch = 0 ;
                    break;
                case 'supplier' :
                    $typeSearch = 1 ;
                    break;
                case 'other' :
                    $typeSearch = 2;
                    break;
            }

            $query->andWhere('c.type = :type')
                ->setParameter('type', $typeSearch)
            ;
        }

        return $query->getQuery();
    }

    public function findAllNotDeleted($typeSearch)
    {
        $query = $this->findAllNotDeletedQuery($typeSearch);

        return $query->getResult();
    }

    public function findCustomersByName(string $querySearch)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.name LIKE :querySearch')
            ->orWhere('p.email LIKE :querySearch')
            ->setParameter('querySearch', '%'.$querySearch.'%')
        ;

        return $query
            ->getQuery()
            ->getResult()
            ;
    }

    public function countAllCustomers()
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.isDeleted = 0')
            ->select('COUNT(c.id)')
        ;

        return $query
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function countAllItemsByMonth($month, $year)
    {
        $query = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.isDeleted = 0')
            ->andWhere('MONTH(c.createdAt) = :month ')
            ->andWhere('YEAR(c.createdAt) = :year ')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
        ;

        return $query
            ->getQuery()
            ->getSingleResult()
            ;
    }
}

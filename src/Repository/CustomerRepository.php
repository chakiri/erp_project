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
            $query->andWhere('c.type LIKE :type')
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
}

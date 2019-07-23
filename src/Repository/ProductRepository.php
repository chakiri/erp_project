<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }


    //This method return juste query is required for pagination
    public function findAllNotDeletedQuery(?ProductSearch $productSearch): Query
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.isDeleted = false')
        ;

        if ($productSearch != null){
            if ($productSearch->getType()){
                $query
                    ->andWhere('p.type = :type')
                    ->setParameter('type', $productSearch->getType())
                ;
            }

            if ($productSearch->isStocked() === true) {

                $query
                    ->andWhere('p.stock != 0');
            }

            if ($productSearch->isStocked() === false) {

                $query
                    ->andWhere('p.stock = 0');
            }
        }

        return $query->getQuery();
    }



    public function findAllNotDeleted($productSearch = null)
    {
        return $this->findAllNotDeletedQuery($productSearch)
            ->getResult()
        ;
    }

    public function findProductByName(string $querySearch)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.name LIKE :querySearch')
            ->orWhere('p.description LIKE :querySearch')
            ->setParameter('querySearch', '%'.$querySearch.'%')
        ;

        return $query
            ->getQuery()
            ->getResult()
            ;
    }
}

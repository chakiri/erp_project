<?php

namespace App\Service;

use App\Repository\OrderRepository;
use App\Repository\TypeProductRepository;

class Statistics
{
    private $orderRepository;

    private $typeProductRepository;

    public function __construct(OrderRepository $orderRepository, TypeProductRepository $typeProductRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->typeProductRepository = $typeProductRepository;
    }

    /**
     * Function to count nb Items by month (customers, orders, ...)
     * @param $repository
     * @param $function
     * @param $nbMonths
     * @return array
     */
    public function countItemsByMonths($repository, $function, $nbMonths)
    {
        //Get previous nb months
        for ($i=0; $i<$nbMonths; $i++){
            $dates [] = date("Y-m", strtotime(date( 'Y-m-d' )."-$i months"));
        }

        foreach ($dates as $date){
            $month = date("m",strtotime($date));
            $year = date("Y",strtotime($date));

            $nbItems [] = $repository->$function($month, $year);
        }
        $nbItems = array_map('current', $nbItems);

        $results = [
            'dates' => array_reverse($dates),
            'nbItems' => array_reverse($nbItems)
        ];

        return $results;
    }

    /**
     * @return array
     */
    public function countOrdersByTypeProduct()
    {
        foreach ($this->typeProductRepository->findAll() as $result){
            $typesProduct [] = $result->getName();
        }

        foreach ($typesProduct as $type){
            $result =  $this->orderRepository->countAllOrdersByTypeProduct($type);
            $nbOrdersByTypeProduct [] =  reset($result);
        }

        $results = [
            'types' => $typesProduct,
            'nbOrders' => $nbOrdersByTypeProduct
        ];

        return $results;
    }

    public function countOrdersByTypeProductByMonth($nbMonths)
    {
        //Get previous nb months
        for ($i=0; $i<$nbMonths; $i++){
            $dates [] = date("Y-m", strtotime(date( 'Y-m-d' )."-$i months"));
        }

        //Get product types
        foreach ($this->typeProductRepository->findAll() as $result){
            $typesProduct [] = $result->getName();
        }

        foreach ($typesProduct as $type){
            $nbOrdersPerMonth = [];
            foreach ($dates as $date){
                $month = date("m",strtotime($date));
                $year = date("Y",strtotime($date));

                $result = $this->orderRepository->countAllOrdersByTypeProductByMonth($type, $month, $year);
                $nbOrdersPerMonth [] = reset($result);
            }
            $nbOrders [] = array_reverse($nbOrdersPerMonth);
        }

        $results = [
            'dates' => array_reverse($dates),
            'types' => $typesProduct,
            'nbOrders' => $nbOrders
        ];

        return $results;
    }

}
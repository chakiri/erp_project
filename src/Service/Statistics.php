<?php

namespace App\Service;

class Statistics
{

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

}
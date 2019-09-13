<?php

namespace App\Service;

class Statistics
{

    /**
     * Function to count nb Items by month (customers, orders, ...)
     * @param $repository
     * @return array
     */
    public function countItemsByMonths($repository)
    {
        //Get previous 6 months
        for ($i=0; $i<6; $i++){
            $dates [] = date("Y-m", strtotime(date( 'Y-m-d' )."-$i months"));
        }

        foreach ($dates as $date){
            $month = date("m",strtotime($date));
            $year = date("Y",strtotime($date));

            $nbItems [] = $repository->countAllItemsByMonth($month, $year);
        }
        $nbItems = array_map('current', $nbItems);

        $results = [
            'dates' => array_reverse($dates),
            'nbItems' => array_reverse($nbItems)
        ];

        return $results;
    }

}
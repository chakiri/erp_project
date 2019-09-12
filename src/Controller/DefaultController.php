<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/dashboard", name="index_default")
     */
    public function index(CustomerRepository $customerRepository)
    {
        $nbCustomers = $customerRepository->countAllCustomers();
        $nbCustomersByMonth = $this->countCustomersByMonths($customerRepository);

        return $this->render('default/index.html.twig', [
            'nbCustomers' => reset($nbCustomers),
            'nbCustomersByMonth' => $nbCustomersByMonth,
        ]);
    }

    public function countCustomersByMonths($customerRepository)
    {
        //Get previous 6 months
        for ($i=0; $i<6; $i++){
            $dates [] = date("Y-m", strtotime(date( 'Y-m-d' )."-$i months"));
        }

        foreach ($dates as $date){
            $month = date("m",strtotime($date));
            $year = date("Y",strtotime($date));

            $nbCustomers [] = $customerRepository->countAllCustomersByMonth($month, $year);
        }
        $nbCustomers = array_map('current', $nbCustomers);

        $results = [
            'dates' => array_reverse($dates),
            'nbCustomers' => array_reverse($nbCustomers)
        ];

        return $results;
    }

    /**
     * @Route("/search", name="default_search")
     */
    public function searchBar(Request $request, ProductRepository $productRepository, CustomerRepository $customerRepository, OrderRepository $orderRepository)
    {
        $query = $request->request->get('query');
        $results = [];

        if ($query){
            $resultsProduct = $productRepository->findProductsByName($query);
            $resultsCustomer = $customerRepository->findCustomersByName($query);
            $resultsOrder = $orderRepository->findOrdersByName($query);

            if ($resultsProduct) array_push($results, $resultsProduct);
            if ($resultsCustomer) array_push($results, $resultsCustomer);
            if ($resultsOrder) array_push($results, $resultsOrder);
        }

        return $this->render('default/searchResult.html.twig', [
            'results' => $results,
            'querySearch' => $query
        ]);
    }

    public function messenger()
    {
        return $this->render('default/messenger.html.twig');
    }

}

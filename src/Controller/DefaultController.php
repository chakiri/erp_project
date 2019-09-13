<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\Statistics;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/dashboard", name="index_default")
     */
    public function index(CustomerRepository $customerRepository, OrderRepository $orderRepository, Statistics $statistics)
    {
        $nbCustomers = $customerRepository->countAllCustomers();
        $nbCustomersByMonth = $statistics->countItemsByMonths($customerRepository);

        $nbOrders = $orderRepository->countAllOrders();
        $nbOrdersByMonth = $statistics->countItemsByMonths($orderRepository);

        return $this->render('default/index.html.twig', [
            'nbCustomers' => reset($nbCustomers),
            'nbCustomersByMonth' => $nbCustomersByMonth,
            'nbOrders' => reset($nbOrders),
            'nbOrdersByMonth' => $nbOrdersByMonth,
        ]);
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

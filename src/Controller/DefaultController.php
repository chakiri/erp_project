<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ProfileRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/dashboard", name="index_default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
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

}

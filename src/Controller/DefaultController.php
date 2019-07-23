<?php

namespace App\Controller;

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
    public function searchBar(Request $request, ProductRepository $productRepository)
    {
        $query = $request->request->get('query');
        if ($query){
            $results = $productRepository->findProductByName($query);
        }

        return $this->render('default/searchResult.html.twig', [
            'results' => $results
        ]);
    }

}

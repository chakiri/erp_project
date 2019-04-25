<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(ProductRepository $repository)
    {
        $products = $repository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     * @Route("/product/new", name="product_new")
     */
    public function form(Product $product = null, ObjectManager $manager ,Request $request)
    {
        if ($product == null){
            $product = new Product();
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($product);

            $manager->flush();

            return $this->redirectToRoute("product_show", [
               'id' => $product->getId()
            ]);
        }

        return $this->render('product/new.html.twig', [
            'formProduct' => $form->createView(),
            'editMode' => $product->getId() !== null
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Product $product){

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}

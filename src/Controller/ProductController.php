<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_ index")
     */
    public function index(ProductRepository $repository)
    {
        $products = $repository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="product_edit")
     * @Route("/new", name="product_new")
     */
    public function form(Product $product = null, ObjectManager $manager ,Request $request)
    {
        $user = $this->getUser();
        if ($product == null){
            $product = new Product();

            $product->setCreatedBy($user);
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

        return $this->render('product/form.html.twig', [
            'formProduct' => $form->createView(),
            'editMode' => $product->getId() !== null
        ]);
    }

    /**
     * @Route("/{id}", name="product_show")
     */
    public function show(Product $product)
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/delete/{id}", name="product_delete")
     */
    public function delete(ObjectManager $manager, Product $product)
    {
        if ($product){
            $product->setIsDeleted(true);

            $manager->persist($product);

            $manager->flush();

            $this->addFlash('success', 'The product has been remove !');
        }

        return $this->redirectToRoute('product_ index');
    }
}

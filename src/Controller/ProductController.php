<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductSearch;
use App\Form\ProductSearchType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


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
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator)
    {
        $productSearch = new ProductSearch();
        $productSearchForm = $this->createForm(ProductSearchType::class, $productSearch);
        $productSearchForm->handleRequest($request);

        $products = $paginator->paginate(
            $productRepository->findAllNotDeletedQuery($productSearch),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'productSearchForm' => $productSearchForm->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="product_edit")
     * @Route("/new", name="product_new")
     */
    public function form(Product $product = null, ObjectManager $manager ,Request $request)
    {
        if ($product == null){
            $product = new Product();

            $user = $this->getUser();
        }else{
            $user = $product->getCreatedBy();
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $product->setCreatedBy($user);

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

            $this->addFlash('success', 'The product has been removed !');
        }else{
            $this->addFlash('error', ' !');
        }

        return $this->redirectToRoute('product_ index');
    }
}

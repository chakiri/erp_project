<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Customer controller.
 *
 * @Route("customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/", name="customer")
     */
    public function index(CustomerRepository $customerRepository)
    {
        $customers = $customerRepository->findAll();

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="customer_edit")
     * @Route("/new", name="customer_new")
     */
    public function form(Customer $customer = null, ObjectManager $manager, Request $request)
    {
        if ($customer == null) {
            $customer = new Customer();
        }

        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($customer);

            $manager->flush();

            return $this->redirectToRoute("customer_show", [
                'id' => $customer->getId()
            ]);
        }

        return $this->render('customer/new.html.twig', [
            'formCustomer' => $form->createView(),
            'editMode' => $customer->getId() !== null
        ]);
    }

    /**
     * @Route("/{id}", name="customer_show")
     */
    public function show(Customer $customer)
    {

        return $this->render('customer/show.html.twig', [
            'customer' => $customer
        ]);
    }
}
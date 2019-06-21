<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;

use App\Service\CodeGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Order Controller
 *
 * @Route("order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order_index")
     */
    public function index(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->findAll();

        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/new", name="order_new")
     * @Route("/edit/{id}", name="order_edit")
     */
    public function form(Order $order = null, Request $request, ObjectManager $manager, CodeGenerator $codeGenerator)
    {
        if (!$order){
            $order = new Order();

            $order->setReference($codeGenerator->getCode(10));
        }

        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            foreach($order->getOrderItems() as $orderItem){
                $orderItem->setPrice($orderItem->getProduct()->getPrice() * $orderItem->getQuantity());
            }

            $manager->persist($order);

            $manager->flush();

            return $this->redirectToRoute("order_show", ['id' => $order->getId()]);
        }

        return $this->render("order/form.html.twig", [
            "formOrder" => $form->createView(),
            "editMode" => $order->getId() !== null
        ]);
    }

    /**
     * @Route("/{id}", name="order_show")
     */
    public function show(Order $order)
    {
        return $this->render('order/show.html.twig', [
            'order' => $order
        ]);
    }
}

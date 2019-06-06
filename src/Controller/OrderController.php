<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}

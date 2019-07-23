<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;

use App\Service\CodeGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
    public function index(Request $request, OrderRepository $orderRepository, PaginatorInterface $paginator)
    {
        $statusSearch = $request->get("status");
        $timeSearch = $request->get("time");

        $orders = $paginator->paginate(
            $orderRepository->findAllNotDeletedQuery($statusSearch, $timeSearch),
            $request->query->getInt('page', 1),
            5
        );

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

            $totalOrderPrice = 0;
            foreach($order->getOrderItems() as $orderItem){
                $orderItem->setPrice($orderItem->getProduct()->getPrice() * $orderItem->getQuantity());
                $totalOrderPrice = $totalOrderPrice + $orderItem->getPrice();
            }
            $order->setPrice($totalOrderPrice);

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

    /**
     * @Route("/delete/{id}", name="order_delete")
     */
    public function delete(ObjectManager $manager, Order $order)
    {
        if ($order){
            $order->setIsDeleted(true);

            $manager->persist($order);

            $manager->flush();

            $this->addFlash('success', 'The order has been removed !');
        }else{
            $this->addFlash('error', ' !');
        }

        return $this->redirectToRoute('order_index');
    }

    /**
     * @Route("/export/csv", name="order_export")
     */
    public function exportCsv(OrderRepository $orderRepository)
    {
        $response = new StreamedResponse();

        $response->setCallback(function () use ($orderRepository) {

            $handle = fopen('php://output', 'w+');

            fputcsv($handle, ['Reference', 'Date', 'State', 'Customer', 'Product & Quantity', 'isDeleted'], ';');

            $results = $orderRepository->findAllNotDeleted();

            foreach($results as $result){

                //get Products order
                $products = [];
                foreach($result->getOrderItems() as $orderItem){
                    array_push($products, $orderItem->getProduct()->getName() . ' Qnt:' . $orderItem->getQuantity());
                }

                fputcsv($handle, [
                    $result->getReference(),
                    $result->getDateOrder()->format('d-m-Y H:i:s'),
                    $result->getState(),
                    $result->getCustomer(),
                    implode(',', $products),
                    $result->getIsDeleted(),
                ], ';');
            }

            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="export-orders.csv"');

        return $response;
    }

    /**
     * @Route("/{id}/invoice/pdf", name="order_invoice")
     */
    public function generateInvoicePdf(Order $order)
    {
        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', TRUE);
        $pdfOptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('order/invoice.html.twig', [
            'order' => $order
        ]);

        $dompdf->loadHtml($html);

        $dompdf->render();

        $dompdf->stream("invoice".$order->getReference().".pdf", [
            "Attachment" => false
        ]);
    }
}

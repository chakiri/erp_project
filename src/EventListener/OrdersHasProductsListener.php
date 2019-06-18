<?php

namespace App\EventListener;


use App\Entity\Order;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

class OrdersHasProductsListener
{
    private $order;

    private $flush;

    public function postPersist(LifecycleEventArgs $args)
    {
        if (!$args->getEntity() instanceof Order){
            return;
        }

        $this->order = $args->getEntity();


        $manager = $args->getEntityManager();


        foreach ($this->order->getOrdersHasProducts() as $ordersHasProduct){
            $ordersHasProduct->setOrder($this->order);

            $manager->persist($ordersHasProduct);

        }
        $manager->flush();

    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (!$this->flush == true){
            return;
        }

        $manager = $args->getEntityManager();

        $manager->persist($this->order);

        $manager->flush();
    }
}
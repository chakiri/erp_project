<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersHasProductsRepository")
 */
class OrderItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="orderItems")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderItems", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private $order;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $quantity;

    public function __construct(Order $order)
    {
        $this->setOrder($order);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}

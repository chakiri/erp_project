<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table("`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateOrder;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @Assert\NotBlank()
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->dateOrder = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateOrder(): ?string
    {
        return $this->dateOrder;
    }

    public function setDateOrder(string $dateOrder): self
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param mixed $orderItems
     */
    public function setOrderItems($orderItems)
    {
        $this->orderItems = $orderItems;
    }

    public function addOrderItem(OrderItem $orderItems)
    {
        if (!$this->orderItems->contains($orderItems)) {
            $this->orderItems->add($orderItems);
        }
    }

    public function removeOrderItem(OrderItem $orderItems)
    {
        if ($this->orderItems->contains($orderItems)) {
            $this->orderItems->removeElement($orderItems);
        }
    }

    /**
     * @return int
     */
    public function getOrderItemsCount()
    {
        return $this->orderItems->count();
    }
}

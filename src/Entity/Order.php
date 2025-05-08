<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "orders")]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;
    
    #[ORM\ManyToOne(targetEntity: Cart::class)]
    #[ORM\JoinColumn(name: "cart_uuid", referencedColumnName: "uuid", onDelete: "CASCADE")]
    private ?Cart $cart = null;
    
    #[ORM\ManyToOne(targetEntity: OrderStatus::class)]
    #[ORM\JoinColumn(name: "status", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?OrderStatus $status = null;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

     public function getCart(): ?Cart
    {
        return $this->cart;
    }
    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStaus(OrderStatus $status): void
    {
        $this->status = $status;
    }
    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }
}



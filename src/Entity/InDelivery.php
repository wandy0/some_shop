<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "in_delivery")]
class InDelivery
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;
    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_uuid", referencedColumnName: "uuid", unique:"true")]
    private ?User $user = null;
    
    #[ORM\ManyToOne(targetEntity: Item::class)]
    #[ORM\JoinColumn(name: "item_uuid", referencedColumnName: "uuid", unique:"true")]
    private ?Item $item = null;
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

     public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(Item $item): void
    {
        $this->item = $item;
    }
}


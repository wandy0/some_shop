<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "warehouses")]
class Warehouse
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $address = "";
    
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

     public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}


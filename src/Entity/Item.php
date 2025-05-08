<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "items")]
class Item
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;

    #[ORM\Column(type: "json")]
    private ?array $info = [];
    
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: "product_uuid", referencedColumnName: "uuid")]
    private ?Product $product = null;
    
    #[ORM\ManyToOne(targetEntity: ItemStatus::class)]
    #[ORM\JoinColumn(name: "status", referencedColumnName: "id")]
    private ?ItemStatus $status = null;
    
    #[ORM\Column(type: "uuid")]
    private ?UuidInterface $location_uuid = null;
    
    #[ORM\Column(type: "boolean")]
    private ?bool $accessible = false;
    
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

     public function getInfo(): ?array
    {
        return $this->info;
    }

    public function setInfo(array $chars): void
    {
        $this->info = $chars;
    }
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }
    public function getStatus(): ?ItemStatus
    {
        return $this->status;
    }
    public function setStatus(?ItemStatus $item_status)
    {
        $this->status = $item_status;
    }
    public function setPrice(?ItemStatus $status): void
    {
        $this->status = $status;
    }
    public function getLocationUuid(): ?UuidInterface
    {
        return $this->location_uuid;
    }
    
    public function setLocationUuid(?UuidInterface $location_uuid): void
    {
        $this->location_uuid= $location_uuid;
    }
    public function getAccessibility(): ?bool
    {
        return $this->accessible;
    }
    
    public function setAccessibility(?bool $accessible): void
    {
        $this->accessible= $accessible;
    }
}

<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "prices")]
class Price
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;
    
    
    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $price = null;
    
    #[ORM\ManyToOne(targetEntity: Sale::class)]
    #[ORM\JoinColumn(name: "sale_uuid", referencedColumnName: "uuid")]
    private ?Sale $sale = null;
    
    #[ORM\OneToOne(targetEntity: Product::class, inversedBy: 'price')]
    #[ORM\JoinColumn(name: 'product_uuid', referencedColumnName: 'uuid', nullable: false)]
    private Product $product;
    
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

     public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }
    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): void
    {
        $this->sale = $sale;
    }
}


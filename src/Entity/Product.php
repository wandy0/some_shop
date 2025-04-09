<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "products")]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $info = [];
    
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "category_uuid", referencedColumnName: "uuid", onDelete: "CASCADE")]
    private ?Category $category = null;
    
     #[ORM\OneToOne(mappedBy: 'product', targetEntity: Price::class)]
    private ?Price $price = null;
    
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
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }
    public function getPrice(): ?Price
    {
        return $this->price;
    }
    public function setPrice(?Price $price): void
    {
        $this->price = $price;
    }
}



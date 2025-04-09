<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "categories")]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;
    
    #[ORM\Column(type: "uuid",nullable: true)]
    private ?UuidInterface $parent_uuid;
    
    #[ORM\Column(type: "string", length: 64, nullable: true)]
    private string $category_name;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $prod_characteristics = [] ;
    
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $name): void
    {
        $this->category_name = $name;
    }

    public function getParentUuid(): ?UuidInterface
    {
        return $this->parent_uuid;
    }

    public function setParentUuid(UuidInterface $uuid): void
    {
        $this->parent_uuid = $uuid;
    }
     public function getCharacteristics(): ?string
    {
        return $this->prod_characteristics;
    }

    public function setCharacteristics(?array $chars): void
    {
        $this->prod_characteristics = $chars;
    }
}

?>

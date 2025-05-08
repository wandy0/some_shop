<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "item_statuses")]
class ItemStatus
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", unique:"true")]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: "string", nullable: true, length: 128)]
    private ?string $name = "";
    
    
    
    public function getid(): int
    {
        return $this->id;
    }

     public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    
}



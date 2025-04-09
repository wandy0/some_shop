<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "Roles")]
class Role
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $info = [];
    
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
}


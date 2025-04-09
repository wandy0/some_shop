<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique:"true")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $uuid;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $info = [];
    
    #[ORM\OneToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(name: "role_uuid", referencedColumnName: "uuid")]
    private ?Role $role = null;
    
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
    
    public function getRole(): ?Role
    {
        return $this->price;
    }
    public function setRole(?Role $role): void
    {
        $this->role = $role;
    }
}



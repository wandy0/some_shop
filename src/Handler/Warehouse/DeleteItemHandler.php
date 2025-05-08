<?php

namespace App\Handler\Warehouse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Item;

class DeleteItemHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteItem($uuid) : void
    {
        $item = $this->entityManager
                ->getRepository(Item::class)
                ->findOneBy(["uuid"=>$uuid]);
        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }
}


<?php

namespace App\Handler\Warehouse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Ramsey\Uuid\Uuid;
use App\Entity\Item;
use App\Entity\Product;
use App\Entity\ItemStatus;

class PostItemHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postItem(array $info,string $product_uuid, int $status, 
                                string $location_uuid, bool $accessible) : void
    {
        $product = $this->entityManager
                ->getRepository(Product::class)
                ->findOneBy(["uuid"=>Uuid::fromString($product_uuid)]);
        $item_status = $this->entityManager
                ->getRepository(ItemStatus::class)
                ->find($status);
        $item = new Item();
        $item->setInfo($info);
        $item->setProduct($product);
        $item->setLocationUuid(Uuid::fromString($location_uuid));
        $item->setStatus($item_status);
        $item->setAccessibility($accessible);
        $this->entityManager->persist($item);     
        $this->entityManager->flush();
    }
}


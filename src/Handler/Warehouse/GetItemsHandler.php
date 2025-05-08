<?php

namespace App\Handler\Warehouse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Item;
use Ramsey\Uuid\Uuid;


class GetItemsHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getItems($uuid)
    {
        $uuid = Uuid::fromString($uuid);
        $items = $this->entityManager
                ->getRepository(Item::class)
                ->findBy(["location_uuid"=>$uuid]);
        if ($items){
            $response = [];
            foreach ($items as $item){
                array_push($response, ['uuid'=>$item->getUuid(),
                                       'product_uuid'=>$item->getProduct()->getUuid(),
                                       'location_uuid'=>$uuid,
                                       'info'=>$item->getInfo(),
                                       'status'=>$item->getStatus()->getId(),
                                       'accessible'=>$item->getAccessibility()]);
            }
            return json_encode($response);
        }
        else{
            return json_encode(["error"=>404]);
        }
    }
}


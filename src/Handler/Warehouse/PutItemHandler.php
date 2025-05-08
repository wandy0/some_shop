<?php

namespace App\Handler\Warehouse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Item;
use App\Entity\ItemStatus;

class PutItemHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function putItem($uuid,
                            $info=[],
                            $location_uuid = null,
                            $status = null,
                            $accessible = null,
                            $flush = true) : void
    {
                        
        $item = $this->entityManager
                ->getRepository(Item::class)
                ->findOneBy(["uuid"=>$uuid]);
        if($info){
            $item->setInfo($info);
        }
        if($location_uuid){
            $item->setLocationUuid($location_uuid);
        }
        if($status){
            $status = $this->entityManager->getRepository(ItemStatus::class)
                           ->find($status);
            $item->setStatus($status);
        }
        if(!is_null($accessible)){
            $item->setAccessibility($accessible);
        }
        if ($flush){
            $this->entityManager->flush();
        }
    }
}


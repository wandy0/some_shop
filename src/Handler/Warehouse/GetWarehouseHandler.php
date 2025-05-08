<?php

namespace App\Handler\Warehouse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Warehouse;


class GetWarehouseHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getWarehouses()
    {
        $warehouses = $this->entityManager
                ->getRepository(Warehouse::class)
                ->findAll();
        if ($warehouses){
            $response = [];
            foreach ($warehouses as $warehouse){
                array_push($response, ['uuid'=>$warehouse->getUuid(),
                                       'address'=>$warehouse->getAddress()]);
            }
            return json_encode($response);
        }
        else{
            return json_encode(["error"=>404]);
        }
    }
}


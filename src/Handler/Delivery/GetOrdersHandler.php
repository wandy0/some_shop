<?php

namespace App\Handler\Delivery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Order;


class GetOrdersHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getOrders()
    {
        $orders = $this->entityManager
                ->getRepository(Order::class)
                ->findAll();
        if ($orders){
            $response = [];
            foreach ($orders as $order){
                array_push($response, ['uuid'=>$order->getUuid(),
                                       'cart'=> $order->getCart()->getUuid(),
                                       'status'=>$order->getStatus()->getName()]);
            }
            return json_encode($response);
        }
        else{
            return json_encode(["error"=>404]);
        }
    }
}


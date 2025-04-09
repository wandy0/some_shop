<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Cart;

class GetCartHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCart($uuid)
    {
        $cart = $this->entityManager->getRepository(Cart::class)->findBy(["user"=>$uuid]);
        if ($cart){
            $response =[];
            foreach($cart as $c){
                array_push($response,["uuid"=>$c->getUuid()]);
            }
            return json_encode($response);
        }
        else{
            return json_encode(["error"=>404]);
        }
    }
}


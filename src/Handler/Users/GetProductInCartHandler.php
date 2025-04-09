<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ProductInCart;

class GetProductInCartHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getProducts($uuid)
    {
        $content = $this->entityManager->getRepository(ProductInCart::class)->findBy(["cart"=>$uuid]);
        if ($content){
            $response =[];
            foreach($content as $c){
                array_push($response,["product_uuid"=>$c->getProduct()->getUuid(),
                                      "amount"=>$c->getAmount()]);
            }
            return json_encode($response);
        }
        else{
            return json_encode(["error"=>404]);
        }
    }
}


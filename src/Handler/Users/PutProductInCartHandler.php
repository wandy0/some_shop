<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ProductInCart;

class PutProductInCartHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function putProduct( $cart_uuid,
                                $product_uuid,
                                int $amount) : void
    {
        $productincart = $this->entityManager
                ->getRepository(ProductInCart::class)
                ->findOneBy(["cart"=>$cart_uuid,"product"=>$product_uuid]);
        if($amount){
            $productincart->setAmount($amount);
        }   
        $this->entityManager->flush();
    }
}

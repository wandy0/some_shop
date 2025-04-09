<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\ProductInCart;

class PostProductInCartHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addProduct($cart_uuid,$product_uuid,int $amount) : void
    {
        $cart = $this->entityManager
                     ->getRepository(Cart::class)
                     ->findOneBy(["uuid"=>$cart_uuid]);
        $product = $this->entityManager
                        ->getRepository(Product::class)
                        ->findOneBy(["uuid"=>$product_uuid]);
        $productincart = new ProductInCart();
        $productincart->setCart($cart);
        $productincart->setProduct($product);
        $productincart->setAmount($amount);
        $this->entityManager->persist($productincart);     
        $this->entityManager->flush();
    }
}


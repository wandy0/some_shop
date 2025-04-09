<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ProductInCart;

class DeleteProductInCartHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function removeProduct($cart_uuid,$product_uuid) : void
    {
        $productincart = $this->entityManager
                ->getRepository(ProductInCart::class)
                ->findOneBy(["cart" => $cart_uuid,
                             "product" => $product_uuid]);
        $this->entityManager->remove($productincart);
        $this->entityManager->flush();
    }
}

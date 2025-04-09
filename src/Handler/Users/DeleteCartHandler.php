<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Cart;

class DeleteCartHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteCart($uuid) : void
    {
        $cart = $this->entityManager
                ->getRepository(Cart::class)
                ->findOneBy(["uuid"=>$uuid]);
        $this->entityManager->remove($cart);
        $this->entityManager->flush();
    }
}


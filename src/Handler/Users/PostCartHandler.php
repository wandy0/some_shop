<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Cart;
use App\Entity\User;

class PostCartHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postCart($user_uuid) : void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(["uuid"=>$user_uuid]);
        $cart = new Cart();
        $cart->setUser($user);
        
        $this->entityManager->persist($cart);     
        $this->entityManager->flush();
    }
}


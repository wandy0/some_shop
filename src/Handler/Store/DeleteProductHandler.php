<?php

namespace App\Handler\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;

class DeleteProductHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteProduct($uuid) : void
    {
        $product = $this->entityManager
                ->getRepository(Product::class)
                ->findOneBy(["uuid"=>$uuid]);
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}


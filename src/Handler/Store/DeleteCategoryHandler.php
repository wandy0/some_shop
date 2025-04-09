<?php

namespace App\Handler\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;

class DeleteCategoryHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteCategory($uuid) : void
    {
        $product = $this->entityManager
                ->getRepository(Category::class)
                ->findOneBy(["uuid"=>$uuid]);
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}


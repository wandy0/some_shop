<?php

namespace App\Handler\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Entity\Category;

class PostProductHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postProduct(array $info,$category_name) : void
    {
        $category_uuid = $this->entityManager
                ->getRepository(Category::class)
                ->findOneBy(["category_name"=>$category_name]);
        $product = new Product();
        $product->setInfo($info);
        $product->setCategory($category_uuid);

        $this->entityManager->persist($product);     
        $this->entityManager->flush();
    }
}


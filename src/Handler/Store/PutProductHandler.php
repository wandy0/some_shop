<?php

namespace App\Handler\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Entity\Category;

class PutProductHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function putProduct( $uuid,
                               $info=[],
                               ?string $category_name=null) : void
    {
        $product = $this->entityManager
                ->getRepository(Product::class)
                ->findOneBy(["uuid"=>$uuid]);
        if($category_name){
            $category_uuid = $this->entityManager
                    ->getRepository(Category::class)
                    ->findOneBy(["category_name"=>$category_name]);
            $product->setCategory($category_uuid);
        }
        if($info){
            $product->setInfo($info);
        }   
        $this->entityManager->flush();
    }
}



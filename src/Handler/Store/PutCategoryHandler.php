<?php

namespace App\Handler\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;

class PutCategoryHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function putCategory( $uuid,
                               ?string $parent_name=null,
                               ?string $category_name=null,
                               ?array $prod_characteristics=null) : void
    {
        $category = $this->entityManager
                ->getRepository(Category::class)
                ->findOneBy(["uuid"=>$uuid]);
        if($category_name){
            $category->setCategoryName($category_name);
        }
        if($parent_name){
            $category->setParentUuid($this->entityManager
                ->getRepository(Category::class)
                ->findOneBy(["category_name"=>$parent_name])
                ->getUuid());
        }
        if($prod_characteristics){
            $category->setCharacteristics($prod_characteristics);
        }
        $this->entityManager->flush();
    }
}


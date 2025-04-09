<?php

namespace App\Handler\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;

class PostCategoryHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postCategory($parent_name,$category_name,$prod_characteristics) : void
    {
        if ($parent_name!="root"){
        $parent_uuid = $this->entityManager
                ->getRepository(Category::class)
                ->findOneBy(["category_name"=>$parent_name])
                ->getUuid();
        }
        else{
            $parent_uuid = null;
        }
        $category = new Category();
        $category->setCategoryName($category_name);
        $category->setParentUuid($parent_uuid);
        $category->setCharacteristics($prod_characteristics);
        $this->entityManager->persist($category);     
        $this->entityManager->flush();
    }
}

<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Role;

class PostUserHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postUser(array $info) : void
    {
        
        $role=$this->entityManager->createQueryBuilder()
                   ->select("r")
                   ->from(Role::class,"r")
                   ->where("JSON_GET_FIELD_AS_TEXT(r.info,'name')='guest'")
                   ->getQuery()->getResult()[0];
        $user = new User();
        $user->setInfo($info);
        $user->setRole($role);

        $this->entityManager->persist($user);     
        $this->entityManager->flush();
    }
}

